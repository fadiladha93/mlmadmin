<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use DataTables;

use Illuminate\Support\Facades\Storage;

class MediaController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
                'getVideoView',
                'viewVideo',
                'getImageView',
                'getDocView',
                'getPresentationView',
                'downloadMedia'
        ]]);
        $this->middleware('auth.affiliate');

    }

    public function adminIndex() {
        return view('admin.media.index');
    }

    public function frmNew() {
        return view('admin.media.frmNew');
    }

    public function validateMediaRec() {
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        } else {
            return response()->json(['error' => 0]);
        }
    }

    public function frmEdit($recId) {
        $d = array();
        $d['rec'] = DB::table('media')
                ->where('id', $recId)
                ->first();
        return view('admin.media.frmEdit')->with($d);
    }

    public function saveMedia() {

        $req = request();
        $rec = new \App\Media();
        //
        $rec->display_name = $req->display_name;
        $rec->category = $req->category;
        $rec->is_active = $req->is_active == "on" ? 1 : 0;
        $rec->is_downloadable = $req->is_downloadable == "on" ? 1 : 0;
        //
        if ($req->uploaded_from == "web") {
            $rec->is_external = 1;
            $rec->external_url = $req->external_url;
        } else {
            $rec->is_external = 0;
            if ($req->hasFile('media_file')) {
                $extension = $req->media_file->getClientOriginalExtension();
                $fileName = $req->media_file->getClientOriginalName();
                $fileName = \App\Media::getUniqueFileName($fileName, $extension);
                
                # Upload to S3 storage
                Storage::putFileAs('media_files', $req->file('media_file'), $fileName);

                # Upload to local storage
                #$req->media_file->move(public_path('/media_files'), $fileName);

                $rec->file_name = $fileName;
                $rec->extension = $extension;
            }
        }

        $rec->save();
        //
        return redirect('/media');
    }

    public function updateMedia() {
        $req = request();
        $recId = $req->rec_id;
        $rec = \App\Media::find($recId);
        //
        $rec->display_name = $req->display_name;
        $rec->category = $req->category;
        $rec->is_active = $req->is_active == "on" ? 1 : 0;
        $rec->is_downloadable = $req->is_downloadable == "on" ? 1 : 0;
        //
        if ($req->uploaded_from == "web") {
            $rec->is_external = 1;
            $rec->external_url = $req->external_url;
            if (!\utill::isNullOrEmpty($rec->file_name)) {
                #unlink(public_path('/media_files') . "/" . $rec->file_name);
                $rec->file_name = null;
            }
        } else {
            $rec->is_external = 0;
            if ($req->hasFile('media_file')) {
                // unlink previous file
                // unlink(public_path('/media_files') . "/" . $rec->file_name);
                //
                $extension = $req->media_file->getClientOriginalExtension();
                $fileName = $req->media_file->getClientOriginalName();
                $fileName = \App\Media::getUniqueFileName($fileName, $extension);

                # Upload to S3 storage
                Storage::putFileAs('media_files', $req->file('media_file'), $fileName);

                # Upload to local storage
                // $req->media_file->move(public_path('/media_files'), $fileName);
                //
                $rec->file_name = $fileName;
                $rec->extension = $extension;
            }
        }
        $rec->save();
        //
        return redirect('/media');
    }

    public function getDataTable() {
        $query = DB::table('media');
        return DataTables::of($query)->toJson();
    }

    public function getVideoView() {
        $d = array();
        $req = request();
        $q = $req->q;
        $d['recs'] = \App\Media::getRecs(\App\Media::TYPE_VIDEO, $q);
        $v = (string) view('affiliate.media.video_view')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

    public function viewVideo($recId) {
        $d = array();
        $d['rec'] = \App\Media::getRecById($recId);
        return view('affiliate.media.dlg_view_video')->with($d);
    }

    public function getImageView() {
        $d = array();
        $req = request();
        $q = $req->q;
        $d['recs'] = \App\Media::getRecs(\App\Media::TYPE_IMAGE, $q);
        $v = (string) view('affiliate.media.image_view')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

    public function getDocView() {
        $d = array();
        $req = request();
        $q = $req->q;
        $d['recs'] = \App\Media::getRecs(\App\Media::TYPE_DOCUMENT, $q);
        $v = (string) view('affiliate.media.doc_view')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

    public function getPresentationView() {
        $d = array();
        $req = request();
        $q = $req->q;
        $d['recs'] = \App\Media::getRecs(\App\Media::TYPE_PRESENTATION, $q);
        $v = (string) view('affiliate.media.pres_view')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

    public function downloadMedia($fileName) {
        $file = Storage::path('/media_files/' . $fileName);
        header('Content-type: octet/stream');
        header('Content-disposition: attachment; filename=' . $fileName . ';');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

//    private function uploadToS3($path, $fileName) {
//        $s3Util = new \s3Utill();
//        //foreach (glob($folderPath . "/*.*") as $path) {
//            //$fileName = pathinfo($path, PATHINFO_BASENAME);
//            $s3Util->uploadToAmazon($path, $fileName);
//        //}
//    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'display_name' => 'required',
                    'category' => 'required',
                    'external_url' => 'url|nullable',
                        ], [
                    'display_name.required' => 'Display name is required',
                    'category.required' => 'Category is required',
                    'external_url.url' => 'Invalid external URL',
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

}
