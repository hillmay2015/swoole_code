<?php
/**
 * User: may
 * Date: 2019/6/19
 * Time: 下午2:40
 */
namespace app\admin\controller;

use app\common\lib\Util;

class Image
{
    public function index()
    {
        $file = request()->file('file');
        $info = $file->move('upload');
        if ($info) {
            $data = ['image' => config('live.host') . '/upload/' . $info->getSaveName()];
            return Util::show(config('code.success'), 'ok', $data);
        }else{
            return Util::show(config('code.error'), 'error');
        }

    }

}