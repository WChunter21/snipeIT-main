<?php

return array(

    'undeployable' 		=> '<strong>警告: </strong>该资产目前已经被标记为不可被分配，如果该资产状态已经改变，请刷新。',
    'does_not_exist' 	=> '资产不存在',
    'does_not_exist_or_not_requestable' => '已重试，那个资产不存在或不可申请。',
    'assoc_users'	 	=> '这个资产目前已经借给某个用户，不能被删除，请检查资产信息，然后再尝试删除。',

    'create' => array(
        'error'   		=> '资产创建失败，请重试。:(',
        'success' 		=> '资产创建成功。 :)'
    ),

    'update' => array(
        'error'   			=> '资产更新失败，请重试。',
        'success' 			=> '资产更新成功。',
        'nothing_updated'	=>  '一个也没有选，所以什么也不会更新。',
    ),

    'restore' => array(
        'error'   		=> '资产未被恢复，请重试。',
        'success' 		=> '资产恢复成功。'
    ),

    'deletefile' => array(
        'error'   => '文件删除失败，请重试',
        'success' => '文件已成功删除。',
    ),

    'upload' => array(
        'error'   => '文件上传失败，请重试。',
        'success' => '文件已上传成功。',
        'nofiles' => '尚未选择要上传的文件，或上传的文件过大。',
        'invalidfiles' => '一个或多个文件过大或者属于不被允许的文件类型。允许上传的文件类型有PNG，GIF，JPG，DOC，DOCX，PDF和TXT。',
    ),

    'import' => array(
<<<<<<< HEAD
        'error'         => 'Some items did not import correctly.',
        'errorDetail'   => 'The following Items were not imported because of errors.',
        'success'       => "Your file has been imported",
=======
        'error'                 => '某些字段没有正确导入',
        'errorDetail'           => '以下项由于错误未被导入',
        'success'               => "您的文件已被导入",
        'file_delete_success'   => "Your file has been been successfully deleted",
        'file_delete_error'      => "The file was unable to be deleted",
>>>>>>> 62f5a1b2c7934f534fc8fc8299831fc32e794a72
    ),


    'delete' => array(
        'confirm'   	=> '你确定要删除这个资产吗？',
        'error'   		=> '删除资产的过程中出现了一点儿问题，请重试。',
        'success' 		=> '资产成功被删除。'
    ),

    'checkout' => array(
        'error'   		=> '资产未被借出，请重试',
        'success' 		=> '资产借出成功。',
        'user_does_not_exist' => '无效用户，请重试。',
<<<<<<< HEAD
        'not_available' => 'That asset is not available for checkout!'
=======
        'not_available' => '此资产无法借出'
>>>>>>> 62f5a1b2c7934f534fc8fc8299831fc32e794a72
    ),

    'checkin' => array(
        'error'   		=> '资产还没有借入，请重试。',
        'success' 		=> '资产借入成功。',
        'user_does_not_exist' => '无效用户，请重试。',
        'already_checked_in'  => '资产已经被申领',

    ),

    'requests' => array(
        'error'   		=> '未能成功请求分配资产，请重试。',
        'success' 		=> '成功请求资产',
<<<<<<< HEAD
=======
        'canceled'      => 'Checkout request successfully canceled'
>>>>>>> 62f5a1b2c7934f534fc8fc8299831fc32e794a72
    )

);
