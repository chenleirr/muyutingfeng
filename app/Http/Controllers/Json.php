<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonRequest;

class Json extends Controller
{
    public function get_json(JsonRequest $request)
    {
        $prj_name = $request->get('prj_name');
        $module1_key = $request->get('module1_key');
        $module1_val = $request->get('module1_val');
        $module2_key = $request->get('module2_key');
        $module2_val = $request->get('module2_val');
        $module3_key = $request->get('module3_key');
        $module3_val = $request->get('module3_val');
        
        
        return [
            'errno' => 0,
            'errmsg' => 'success',
            'data' => [
                'log' => [
                    '' . $module1_key . '-' . $prj_name . '' => [
                        '' . $prj_name . '-' . $module1_key . '_add' => [
                            'name' => '新增' . $module1_val . '',
                            'fields' => [
                                'id' => 'id',
                                'createtime' => '创建时间',
                                'updatetime' => '更新时间',
                                'pkey' => '' . $module1_val . '标识',
                                'name' => '' . $module1_val . '名称',
                                'siteurl' => '' . $module1_val . '官网',
                                'icon' => '' . $module1_val . 'icon',
                                'descr' => '' . $module1_val . '描述',
                            ],
                        ],
                        '' . $prj_name . '-' . $module1_key . '_edit' => [
                            'name' => '更新' . $module1_val . '',
                            'fields' => [
                                'pkey' => '' . $module1_val . '标识',
                                'name' => '' . $module1_val . '名称',
                                'siteurl' => '' . $module1_val . '官网',
                                'icon' => '' . $module1_val . 'icon',
                                'descr' => '' . $module1_val . '描述',
                            ],
                        ]
                    ],
                    '' . $module2_key . '-' . $prj_name . '' => [
                        '' . $prj_name . '-' . $module2_key . '_add' => [
                            'name' => '新增' . $module2_val . '',
                            'fields' => [
                                'id' => 'id',
                                'gkey' => '' . $module2_val . '标识',
                                'name' => '' . $module2_val . '名称',
                                'firstchar' => '首字母',
                                'company' => '公司名称',
                                'theme' => '' . $module2_val . '类型',
                                'showenabled' => '' . $module2_val . '是否显示',
                                'icon' => '' . $module2_val . '小图标',
                                'cover' => '' . $module2_val . '图片',
                                'operator' => '' . $module2_val . '运营方',
                                'logintpl' => '进' . $module2_val . '模板',
                                'state' => '' . $module2_val . '状态',
                                'descr' => '' . $module2_val . '描述',

                            ],
                        ],
                        '' . $prj_name . '-' . $module2_key . '_edit' => [
                            'name' => '更新' . $module2_val . '',
                            'fields' => [
                                'id' => 'id',
                                'createtime' => '创建时间',
                                'updatetime' => '更新时间',

                                'pkey' => '' . $module1_val . '标识',
                                'gkey' => '' . $module2_val . '标识',
                                'name' => '' . $module2_val . '名称',
                                'firstchar' => '首字母',
                                'company' => '公司名称',
                                'theme' => '' . $module2_val . '类型',
                                'showenabled' => '' . $module2_val . '是否显示',
                                'icon' => '' . $module2_val . '小图标',
                                'cover' => '' . $module2_val . '图片',
                                'siteurl' => '官网url',
                                'bbsurl' => '论坛url',
                                'operator' => '' . $module2_val . '运营方',
                                'logintpl' => '进' . $module2_val . '模板',
                                'state' => '' . $module2_val . '状态',
                                'descr' => '' . $module2_val . '描述',
                            ],
                        ]
                    ],
                    '' . $module3_key . '-' . $prj_name . '' => [
                        '' . $prj_name . '-' . $module3_key . '_add' => [
                            'name' => '新增' . $module3_val . '',
                            'fields' => [
                                'id' => 'id',
                                'createtime' => '创建时间',
                                'updatetime' => '更新时间',

                                'pkey' => '' . $module1_val . '标识',
                                'gkey' => '' . $module2_val . '标识',
                                'skey' => '' . $module3_val . '标识',
                                'name' => '' . $module3_val . '名称',
                                'mskey' => '合服标识',
                                'state' => '' . $module3_val . '状态',
                                'payenabled' => '是否可支付',
                                'showenabled' => '是否显示',
                            ],
                        ],
                        '' . $prj_name . '-' . $module3_key . '_edit' => [
                            'name' => '更新' . $module3_val . '',
                            'fields' => [
                                'id' => 'id',
                                'createtime' => '创建时间',
                                'updatetime' => '更新时间',

                                'pkey' => '' . $module1_val . '标识',
                                'gkey' => '' . $module2_val . '标识',
                                'skey' => '' . $module3_val . '标识',
                                'name' => '' . $module3_val . '名称',
                                'mskey' => '合服标识',
                                'state' => '' . $module3_val . '状态',
                            ],
                        ],
                        '' . $prj_name . '-' . $module3_key . '_batchupdate' => [
                            'name' => '批量更新' . $module3_val . '',
                            'fields' => [
                                'pkey' => '' . $module1_val . '标识',
                                'gkey' => '' . $module2_val . '标识',
                                'skeys' => '' . $module3_val . '标识',

                                'mskey' => '合服标识',
                                'state' => '' . $module3_val . '状态',
                                'payenabled' => '支付状态',
                                'showenabled' => '显示状态',
                            ],
                        ]
                    ],
                ],
            ],
        ];
    }
}