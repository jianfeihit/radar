<?php
class UserModel extends Model {
	protected $_validate = array(
		array('userId','require','用户ID不能为空'), // 在新增的时候验证name字段是否唯一
		array('pwd','require','用户ID不能为空'),
		array('pwd','repwd','两次输入密码不一致',0,'confirm'), // 验证确认密码是否和密码一致
	);
	protected $_auto = array(
		array('pwd','md5',3,'function')
	);
}
?>