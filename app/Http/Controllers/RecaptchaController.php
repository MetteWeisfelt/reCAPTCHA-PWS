<?php

namespace App\Http\Controllers;

use App\Category;
use App\ControlImage;
use App\Image;
use App\MultiImageResult;
use App\MultiImageResultImage;
use App\Profile;
use App\SingleImageResult;
use App\SingleImageResultCoord;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecaptchaController extends Controller
{
    public function index()
    {
        return view('recaptcha');
    }

    public function recaptcha(Request $request)
    {
        $result = DB::select('SELECT i.image_id, r.category_id, c.name as category_name, COUNT(*) AS count, sum(i.selected = true) as selected, (SELECT sum(multi_image_result_images.selected = true) FROM multi_image_result_images INNER JOIN multi_image_results ON multi_image_result_images.multi_image_result_id = multi_image_results.id WHERE (multi_image_result_images.image_id = i.image_id AND multi_image_results.passed = true AND multi_image_result_images.image_type != "App\\\ControlImage") AND (multi_image_result_images.duration >= 0.60 OR multi_image_result_images.duration = 0.00)) as total_selected, sum(i.selected = true) / COUNT(*) * 100 as category_percentage, sum(i.selected = true) / (SELECT sum(multi_image_result_images.selected = true) FROM multi_image_result_images INNER JOIN multi_image_results ON multi_image_result_images.multi_image_result_id = multi_image_results.id WHERE (multi_image_result_images.image_id = i.image_id AND multi_image_results.passed = true AND multi_image_result_images.image_type != "App\\\ControlImage") AND (multi_image_result_images.duration >= 0.60 OR multi_image_result_images.duration = 0.00)) * 100 as total_percentage FROM multi_image_result_images i INNER JOIN multi_image_results r ON i.multi_image_result_id = r.id INNER JOIN categories c ON r.category_id = c.id WHERE (r.passed = true AND i.image_type != "App\\\ControlImage") AND (i.duration >= 0.60  OR i.duration = 0.00) GROUP BY i.image_id, r.category_id HAVING selected >= 5 AND category_percentage >= 90 ORDER BY RAND();');
        $singleImageAvailable = !empty($result);

        $random = rand(1, 10);

        if ((!$singleImageAvailable || $random <= 3)) {
            $category = Category::inRandomOrder()->first();
            $images = Image::inRandomOrder()->limit(8)->get();

            $controlImage = ControlImage::where('category_id', $category->id)->inRandomOrder()->first();

            $images = $images->push($controlImage)->shuffle();

            $request->session()->put('type', 'multi');
            $request->session()->put('images', $images);
            $request->session()->put('category', $category);

            return view('recaptcha.recaptcha_modal')->with([
                'images' => $images,
                'category' => $category
            ]);
        }
        else {
            $image = Image::find($result[0]->image_id);
            $subcategory = Subcategory::where('category_id', $result[0]->category_id)->inRandomOrder()->first();

            $request->session()->put('type', 'single');
            $request->session()->put('image', $image);
            $request->session()->put('subcategory', $subcategory);

            return view('recaptcha.recaptcha_modal_pieces')->with([
                'image' => $image,
                'subcategory' => $subcategory
            ]);
        }
    }

    public function result(Request $request)
    {
        $imageSelections = $request->input('imageSelections');

        if (!$request->session()->has('identifier')) {
            $this->createProfile($request);
        }

        $profile = Profile::where('identifier', $request->session()->get('identifier'))->first();
        if (!$profile) {
            $profile = $this->createProfile($request);;
        }

        if ($request->session()->get('type') === 'multi') {
            $images = $request->session()->get('images');
            $category = $request->session()->get('category');

            $result = new MultiImageResult();
            $result->profile_id = $profile->id;
            $result->category_id = $category->id;
            $result->passed = false;

            $result->save();

            $failedControlImages = false;
            for ($i = 0; $i < $images->count(); ++$i) {
                $imageType = get_class($images[$i]);
                $imageSelected = $imageSelections[$i]['selected'] === 'true';
                if ($imageType === 'App\ControlImage') {
                    if (!$imageSelected) {
                        $failedControlImages = true;
                    } else if (!$failedControlImages) {
                        $result->passed = true;
                    }
                }

                $resultRow = new MultiImageResultImage();
                $resultRow->image_type = $imageType;
                $resultRow->image_id = $images[$i]->id;
                $resultRow->selected = $imageSelected;
                $resultRow->duration = $imageSelections[$i]['timing'];

                $result->multiImageResultImages()->save($resultRow);
            }

            $result->save();
        }
        else if ($request->session()->get('type') === 'single') {
            $image = $request->session()->get('image');
            $subcategory = $request->session()->get('subcategory');

            $result = new SingleImageResult();
            $result->subcategory_id = $subcategory->id;
            $result->image_id = $image->id;
            $result->profile_id = $profile->id;
            $result->passed = true;

            $result->save();

            for ($i = 0; $i < 9; ++$i) {
                $imageSelected = $imageSelections[$i]['selected'] === 'true';

                $resultRow = new SingleImageResultCoord();
                $resultRow->x = $imageSelections[$i]['x'];
                $resultRow->y = $imageSelections[$i]['y'];
                $resultRow->selected = $imageSelected;
                $resultRow->duration = $imageSelections[$i]['timing'];

                $result->singleImageResultCoords()->save($resultRow);
            }

            $result->save();
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    function createProfile(Request $request)
    {
        $uniqueIdentifier = '';
        $isUnique = false;

        while (!$isUnique) {
            $allowedCharacters = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()_+-=";
            $length = 32;
            $uniqueIdentifier = '';

            for ($i = 0; $i < $length; $i++) {
                $uniqueIdentifier .= substr($allowedCharacters, rand(0, strlen($allowedCharacters) - 1), 1);
            }

            $isUnique = Profile::where('identifier', $uniqueIdentifier)->count();

            $isUnique = $isUnique > 0 ? false : true;
        }

        $profile = new Profile();
        $profile->identifier = $uniqueIdentifier;
        $profile->ipaddress = $request->ip();
        $profile->banned = false;

        $profile->save();

        $request->session()->put('identifier', $uniqueIdentifier);

        return $profile;
    }
}
