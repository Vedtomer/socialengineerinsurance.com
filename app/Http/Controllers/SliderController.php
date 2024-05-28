<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;


class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        return view('admin.slider', compact('sliders'));
    }


    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200', 
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();

            try {
                // Store the image in the storage/slider folder
                $image->storeAs('public/slider', $imageName);

                // Save slider details to the database
                $slider = new Slider();
                $slider->image = $imageName;
                $slider->save();

                return redirect()->route('sliders.index')->with('success', 'Slider created successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to upload image.');
            }
        }

        return redirect()->back()->with('error', 'No image uploaded.');
    }

    public function toggleStatus(Slider $slider)
    {
        $slider->status = !$slider->status;
        $slider->save();

        return response()->json(['success' => true, 'status' => $slider->status]);
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $filename = basename($slider->image);
        Storage::delete('public/slider/' . $filename);
        $slider->delete();

        return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully.');
    }
}
