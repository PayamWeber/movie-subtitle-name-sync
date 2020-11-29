<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/fix_movies', function (Request $request) {
    $files = scandir( $request->folder_path );
    $allowed_ext = [ 'mp4', 'mkv' ];

    $prepath = $request->folder_path . '/';
    $res = [];
    foreach ( $files as $file ){
        if ( is_file( $prepath . $file ) ){
            $re = '/(.*)\.([a-zA-Z0-9]*)/m';
            preg_match_all($re, $file, $matches, PREG_SET_ORDER, 0);
            $only_name = $matches[0][1];
            $ext = $matches[0][2];
            $re = '/s[0-9]{1,2}e[0-9]{1,2}/m';
            preg_match_all($re, strtolower($only_name), $series_matches, PREG_SET_ORDER, 0);
            $series_match = $series_matches[0][0] ?? '';
            $re = '/([a-zA-Z0-9]+)/m';
            preg_match_all($re, $only_name, $word_matches, PREG_SET_ORDER, 0);
            $word_matches = array_map('strtolower', \Illuminate\Support\Arr::flatten( $word_matches ));

            if( ! in_array( $ext, $allowed_ext ) ){
                continue;
            }

            if ( ! file_exists( $prepath . $only_name . '.srt' ) ){
                foreach ( $files as $_file ){
                    $re = '/(.*)\.([a-zA-Z0-9]*)/m';
                    preg_match_all($re, $_file, $_matches, PREG_SET_ORDER, 0);
                    $_only_name = $_matches[0][1];
                    $_ext = $_matches[0][2];

                    if ( $_ext == 'srt' ){
                        $re = '/([a-zA-Z0-9]+)/m';
                        preg_match_all($re, $_only_name, $_word_matches, PREG_SET_ORDER, 0);
                        $_word_matches = array_map('strtolower', \Illuminate\Support\Arr::flatten( $_word_matches ));
                        $re = '/s[0-9]{1,2}e[0-9]{1,2}/m';
                        preg_match_all($re, strtolower($_only_name), $_series_matches, PREG_SET_ORDER, 0);
                        $_series_match = $_series_matches[0][0] ?? '';

                        $res[] = array_intersect( $word_matches, $_word_matches );
                        if ( count( array_intersect( $word_matches, $_word_matches ) ) > count( $word_matches ) / 2 ){
                            if ( file_exists( $prepath . $_file ) ){
                                if ( $series_match && $_series_match && $series_match == $_series_match ){
                                    rename( $prepath . $_file, $prepath . $only_name . '.srt' );
                                }else if ( ! $series_match || ! $_series_match ){
                                    rename( $prepath . $_file, $prepath . $only_name . '.srt' );
                                }
                            }
                        }

                    }
                }
            }

        }
    }
    return [
        'matches' => $res
    ];
});
