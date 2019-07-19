<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index()
    {
        $result = DB::select('SELECT i.image_id, r.category_id, c.name as category_name, COUNT(*) AS count, sum(i.selected = true) as selected, (SELECT sum(multi_image_result_images.selected = true) FROM multi_image_result_images INNER JOIN multi_image_results ON multi_image_result_images.multi_image_result_id = multi_image_results.id WHERE (multi_image_result_images.image_id = i.image_id AND multi_image_results.passed = true AND multi_image_result_images.image_type != "App\\\ControlImage") AND (multi_image_result_images.duration >= 0.60 OR multi_image_result_images.duration = 0.00)) as total_selected, sum(i.selected = true) / COUNT(*) * 100 as category_percentage, sum(i.selected = true) / (SELECT sum(multi_image_result_images.selected = true) FROM multi_image_result_images INNER JOIN multi_image_results ON multi_image_result_images.multi_image_result_id = multi_image_results.id WHERE (multi_image_result_images.image_id = i.image_id AND multi_image_results.passed = true AND multi_image_result_images.image_type != "App\\\ControlImage") AND (multi_image_result_images.duration >= 0.60 OR multi_image_result_images.duration = 0.00)) * 100 as total_percentage FROM multi_image_result_images i INNER JOIN multi_image_results r ON i.multi_image_result_id = r.id INNER JOIN categories c ON r.category_id = c.id WHERE (r.passed = true AND i.image_type != "App\\\ControlImage") AND (i.duration >= 0.60 OR i.duration = 0.00) GROUP BY i.image_id, r.category_id ORDER BY total_percentage DESC, category_percentage DESC;');
        $images = Image::all();

        $imageResult = [];
        foreach ($result as $row) {
            $imageResult[$row->image_id][] = [
                'categoryId' => $row->category_id,
                'categoryName' => $row->category_name,
                'count' => $row->count,
                'selected' => $row->selected,
                'categoryPercentage' => $row->category_percentage,
                'totalSelected' => $row->total_selected,
                'totalPercentage' => $row->total_percentage
            ];
        }

        return view('admin.result.index')->with([
            'images' => $images,
            'imageResult' => $imageResult
        ]);
    }

    public function image_pieces(Request $request)
    {
        $image = Image::find($request->get('image'));

        return view('admin.result.image_pieces')->with([
            'image' => $image
        ]);
    }

    public function piece_result(Request $request)
    {
        $result = DB::select('SELECT r.image_id, r.subcategory_id, s.name as subcategory_name, COUNT(*) AS count, sum(c.selected = true) as selected, (SELECT SUM(c.selected = true) FROM single_image_result_coords c INNER JOIN single_image_results r ON c.single_image_result_id = r.id INNER JOIN subcategories s ON r.subcategory_id = s.id WHERE (r.passed = true AND r.image_id = ' . $request->get('image') . ' AND c.x = ' . $request->get('x') . ' AND c.y = ' . $request->get('y') . ')) as total_selected, sum(c.selected = true) / COUNT(*) * 100 as subcategory_percentage, sum(c.selected = true) / (SELECT SUM(c.selected = true) FROM single_image_result_coords c INNER JOIN single_image_results r ON c.single_image_result_id = r.id INNER JOIN subcategories s ON r.subcategory_id = s.id WHERE (r.passed = true AND r.image_id = ' . $request->get('image') . ' AND c.x = ' . $request->get('x') . ' AND c.y = ' . $request->get('y') . ')) * 100 as total_percentage FROM single_image_result_coords c INNER JOIN single_image_results r ON c.single_image_result_id = r.id INNER JOIN subcategories s ON r.subcategory_id = s.id WHERE (r.passed = true AND r.image_id = ' . $request->get('image') . ' AND c.x = ' . $request->get('x') . ' AND c.y = ' . $request->get('y') . ') AND (c.duration >= 0.60  OR c.duration = 0.00) GROUP BY r.subcategory_id ORDER BY total_percentage DESC, subcategory_percentage DESC;');

        $imagePieceResult = [];
        foreach ($result as $row) {
            $imagePieceResult[] = [
                'subcategoryName' => $row->subcategory_name,
                'count' => $row->count,
                'selected' => $row->selected,
                'totalSelected' => $row->selected,
                'subcategoryPercentage' => $row->subcategory_percentage,
                'totalPercentage' => $row->total_percentage
            ];
        }

        return view('admin.result.piece')->with([
            'imagePieceResult' => $imagePieceResult
        ]);
    }
}
