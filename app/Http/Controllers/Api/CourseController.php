<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses=Course::all();

        if(count($courses)>0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $courses
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $course = Course::find($id);

        if(!is_null($course)) {
            return response([
                'message' => 'Retrieve Course Success',
                'data' => $course
            ], 200);
        }

        return response([
            'message' => 'Course Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData=$request->all();
        $validate=Validator::make($storeData, [
            'nama_kelas' => 'required|max:60|unique:courses',
            'kode' => 'required',
            'biaya_pendaftaran' => 'required|numeric',
            'kapasitas' => 'required|numeric'
        ]);
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $course=Course::create($storeData);
        return response([
            'message' => 'Add Course Success',
            'data' => $course
        ], 200);
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if(is_null($course)) {
            return response([
                'message' => 'Course Not Found',
                'data' => null
            ], 404);
        }

        if($course->delete()) {
            return response([
                'message' => 'Delete Course Success',
                'data' => $course
            ], 200); 
        }

        return response([
            'message' => 'Delete Course Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $course=Course::find($id);
        if(is_null($course)) {
            return response([
                'message' => 'Course Not Found',
                'data' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'nama_kelas' => ['max:60', 'required', Rule::unique('courses')->ignore($course)],
            'kode' => 'required',
            'biaya_pendaftaran' => 'required|numeric',
            'kapasitas' => 'required|numeric'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $course->nama_kelas=$updateData['nama_kelas'];
        $course->kode=$updateData['kode'];
        $course->biaya_pendaftaran=$updateData['biaya_pendaftaran'];
        $course->kapasitas=$updateData['kapasitas'];

        if($course->save()) {
            return response([
                'message' => 'Update Course Success',
                'data' => $course
            ], 200);
        }

        return response([
            'message' => 'Update Course Failed',
            'data' => null,
        ], 400);
    }
}
