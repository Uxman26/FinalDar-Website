<?php

namespace App\Http\Controllers;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Http\Request;
use proj4php\Point;
use proj4php\Proj;
use proj4php\Proj4php;

class GoogleVisionController extends Controller
{
    public function extractText(Request $request)
    {
        $image = $request->file('image');
        $utmZone = "40"; 

        $proj4 = new Proj4php();
        $projUtm = new Proj('EPSG:326' . $utmZone, $proj4); 
        $projWgs84 = new Proj('EPSG:4326', $proj4); 

        if ($image) {
            $googleCredentialsPath = base_path(env('GOOGLE_APPLICATION_CREDENTIALS'));
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleCredentialsPath);

            $imageAnnotator = new ImageAnnotatorClient();

            $imageContent = file_get_contents($image->path());

            $response = $imageAnnotator->textDetection($imageContent);
            $texts = $response->getTextAnnotations();

            $extractedText = '';
            foreach ($texts as $text) {
                $extractedText .= $text->getDescription() . ' ';
            }

            $imageAnnotator->close();

            preg_match_all('/\b(\d{7}\.\d{2})\b\s+\b(\d{6}\.\d{2})\b/', $extractedText, $matches, PREG_SET_ORDER);

            $flightPlanCoordinates = [];

            foreach ($matches as $match) {
                $northing = $match[1];
                $easting = $match[2];
                
            
                $pointSrc = new Point($easting, $northing, $projUtm);
                $pointDst = $proj4->transform($projWgs84, $pointSrc);
            
                $latitude = $pointDst->y;
                $longitude = $pointDst->x;
            
                $flightPlanCoordinates[] = [
                    'lat' => $latitude,
                    'lng' => $longitude,
                ];
            }
            

            return response()->json($flightPlanCoordinates);
        } else {
            return response()->json(['error' => 'Image not found'], 400);
        }
    }
}
