<?php 
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\User;
class Rongyun extends Controller//Userbase//Userbase//
{
	private $appKey = '';
	private	$appSecret = '1KeEVGt6gV';
	private	$jsonPath = "jsonsource/";
	public function gettoken(){//$user_id,$user_name,$user_img
		
		$user = new User();
		$userres = $user->find(56)->toArray();
		$user_id = $userres['user_id'];
		$user_name = $userres['tel'];
		$head_img = 'http://www.pxsk.com'.$userres['head_img'];
// 		print_r($userres);die;
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 获取 Token 方法
		$result = $RongCloud->user()
		->getToken($user_id,$user_name,$head_img);
		echo "getToken    ";
		print_r($result);
		echo "\n";
	}
	
	public function getreuser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 刷新用户信息方法
		$result = $RongCloud->user()->refresh('userId1', 'username', 'http://www.rongcloud.cn/images/logo.png');
		echo "refresh    ";
		print_r($result);
		echo "\n";
	}
		
	public function checkOnline(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 检查用户在线状态 方法
		$result = $RongCloud->user()->checkOnline('userId1');
		echo "checkOnline    ";
		print_r($result);
		echo "\n";
	}
	
	public function block(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 封禁用户方法（每秒钟限 100 次）
		$result = $RongCloud->user()->block('userId4', '10');
		echo "block    ";
		print_r($result);
		echo "\n";
	}
	
	public function unblock(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 解除用户封禁方法（每秒钟限 100 次）
		$result = $RongCloud->user()->unBlock('userId2');
		echo "unBlock    ";
		print_r($result);
		echo "\n";
	}
	
	public function queryblock(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 获取被封禁用户方法（每秒钟限 100 次）
		$result = $RongCloud->user()->queryBlock();
		echo "queryBlock    ";
		print_r($result);
		echo "\n";
	}
	
	public function addblacklist(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 添加用户到黑名单方法（每秒钟限 100 次）
		$result = $RongCloud->user()->addBlacklist('userId1', 'userId2');
		echo "addBlacklist    ";
		print_r($result);
		echo "\n";
	}
	
	public function queryblacklist(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 获取某用户的黑名单列表方法（每秒钟限 100 次）
		$result = $RongCloud->user()->queryBlacklist('userId1');
		echo "queryBlacklist    ";
		print_r($result);
		echo "\n";
	}
	
	public function removeblacklist(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 从黑名单中移除用户方法（每秒钟限 100 次）
		$result = $RongCloud->user()->removeBlacklist('userId1', 'userId2');
		echo "removeBlacklist    ";
		print_r($result);
		echo "\n";
	}
	
	
	public function publishprivate(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		echo ("\n***************** message **************\n");
		// 发送单聊消息方法（一个用户向另外一个用户发送消息，单条消息最大 128k。每分钟最多发送 6000 条信息，每次发送用户上限为 1000 人，如：一次发送 1000 人时，示为 1000 条消息。）
		$result = $RongCloud->message()->publishPrivate('userId1', ["userId2","userid3","userId4"], 'RC:VcMsg',"{\"content\":\"hello\",\"extra\":\"helloExtra\",\"duration\":20}", 'thisisapush', '{\"pushData\":\"hello\"}', '4', '0', '0', '0', '0');
		echo "publishPrivate    ";
		print_r($result);
		echo "\n";
	}
	
	public function publishtemplate(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 发送单聊模板消息方法（一个用户向多个用户发送不同消息内容，单条消息最大 128k。每分钟最多发送 6000 条信息，每次发送用户上限为 1000 人。）
		$result = $RongCloud->message()->publishTemplate(file_get_contents($this->jsonPath.'TemplateMessage.json'));
		echo "publishTemplate    ";
		print_r($result);
		echo "\n";
	}
	
	public function publishsystem(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 发送系统消息方法（一个用户向一个或多个用户发送系统消息，单条消息最大 128k，会话类型为 SYSTEM。每秒钟最多发送 100 条消息，每次最多同时向 100 人发送，如：一次发送 100 人时，示为 100 条消息。）
		$result = $RongCloud->message()->PublishSystem('userId1', ["userId2","userid3","userId4"], 'RC:TxtMsg',"{\"content\":\"hello\",\"extra\":\"helloExtra\"}", 'thisisapush', '{\"pushData\":\"hello\"}', '0', '0');
		echo "PublishSystem    ";
		print_r($result);
		echo "\n";
	}
	
	public function publishsystemtemplate(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 发送系统模板消息方法（一个用户向一个或多个用户发送系统消息，单条消息最大 128k，会话类型为 SYSTEM.每秒钟最多发送 100 条消息，每次最多同时向 100 人发送，如：一次发送 100 人时，示为 100 条消息。）
		$result = $RongCloud->message()->publishSystemTemplate(file_get_contents($this->jsonPath.'TemplateMessage.json'));
		echo "publishSystemTemplate    ";
		print_r($result);
		echo "\n";
	}
	
	public function publishgroup(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 发送群组消息方法（以一个用户身份向群组发送消息，单条消息最大 128k.每秒钟最多发送 20 条消息，每次最多向 3 个群组发送，如：一次向 3 个群组发送消息，示为 3 条消息。）
		$result = $RongCloud->message()->publishGroup('userId', ["groupId1","groupId2","groupId3"], 'RC:TxtMsg',"{\"content\":\"hello\",\"extra\":\"helloExtra\"}", 'thisisapush', '{\"pushData\":\"hello\"}', '1', '1', '0');
		echo "publishGroup    ";
		print_r($result);
		echo "\n";
	}
	
	public function publishdiscussion(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 发送讨论组消息方法（以一个用户身份向讨论组发送消息，单条消息最大 128k，每秒钟最多发送 20 条消息.）
		$result = $RongCloud->message()->publishDiscussion('userId1', 'discussionId1', 'RC:TxtMsg',"{\"content\":\"hello\",\"extra\":\"helloExtra\"}", 'thisisapush', '{\"pushData\":\"hello\"}', '1', '1', '0');
		echo "publishDiscussion    ";
		print_r($result);
		echo "\n";
	}
	
	public function publishchatroom(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 发送聊天室消息方法（一个用户向聊天室发送消息，单条消息最大 128k。每秒钟限 100 次。）
		$result = $RongCloud->message()->publishChatroom('userId1', ["ChatroomId1","ChatroomId2","ChatroomId3"], 'RC:TxtMsg',"{\"content\":\"hello\",\"extra\":\"helloExtra\"}");
		echo "publishChatroom    ";
		print_r($result);
		echo "\n";
	}
	
	public function broadcast(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 发送广播消息方法（发送消息给一个应用下的所有注册用户，如用户未在线会对满足条件（绑定手机终端）的用户发送 Push 信息，单条消息最大 128k，会话类型为 SYSTEM。每小时只能发送 1 次，每天最多发送 3 次。）
		$result = $RongCloud->message()->broadcast('userId1', 'RC:TxtMsg',"{\"content\":\"哈哈\",\"extra\":\"hello ex\"}", 'thisisapush', '{\"pushData\":\"hello\"}', 'iOS');
		echo "broadcast    ";
		print_r($result);
		echo "\n";
	}
	
	public function gethistory(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 消息历史记录下载地址获取 方法消息历史记录下载地址获取方法。获取 APP 内指定某天某小时内的所有会话消息记录的下载地址。（目前支持二人会话、讨论组、群组、聊天室、客服、系统通知消息历史记录下载）
		$result = $RongCloud->message()->getHistory('2014010101');
		echo "getHistory    ";
		print_r($result);
		echo "\n";
	}
	
	public function deletemessage(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 消息历史记录删除方法（删除 APP 内指定某天某小时内的所有会话消息记录。调用该接口返回成功后，date参数指定的某小时的消息记录文件将在随后的5-10分钟内被永久删除。）
		$result = $RongCloud->message()->deleteMessage('2014010101');
		echo "deleteMessage    ";
		print_r($result);
		echo "\n";
	}
	
	
	public function addbadmsg(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		echo ("\n***************** wordfilter **************\n");
		// 添加敏感词方法（设置敏感词后，App 中用户不会收到含有敏感词的消息内容，默认最多设置 50 个敏感词。）
		$result = $RongCloud->wordfilter()->add('money');
		echo "add    ";
		print_r($result);
		echo "\n";
	}
	
	public function getbadmsg(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 查询敏感词列表方法
		$result = $RongCloud->wordfilter()->getList();
		echo "getList    ";
		print_r($result);
		echo "\n";
	}
	
	public function delbadmsg(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 移除敏感词方法（从敏感词列表中，移除某一敏感词。）
		$result = $RongCloud->wordfilter()->delete('money');
		echo "delete    ";
		print_r($result);
		echo "\n";
	}
	
	
	public function creategroup(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		echo ("\n***************** group **************\n");
		// 创建群组方法（创建群组，并将用户加入该群组，用户将可以收到该群的消息，同一用户最多可加入 500 个群，每个群最大至 3000 人，App 内的群组数量没有限制.注：其实本方法是加入群组方法 /group/join 的别名。）
		$result = $RongCloud->group()->create(["userId1","userid2","userId3"], 'groupId1', 'groupName1');
		echo "create    ";
		print_r($result);
		echo "\n";
	}
	
	public function samegroup(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 同步用户所属群组方法（当第一次连接融云服务器时，需要向融云服务器提交 userId 对应的用户当前所加入的所有群组，此接口主要为防止应用中用户群信息同融云已知的用户所属群信息不同步。）
		$groupInfo['gourid1'] = 'gourpName1';
		$groupInfo['gourid2'] = 'gourpName2';
		$groupInfo['gourid3'] = 'gourpName3';
		$result = $RongCloud->group()->sync('userId1', $groupInfo);
		echo "sync    ";
		print_r($result);
		echo "\n";
	}
	
	public function refreshgroup(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 刷新群组信息方法
		$result = $RongCloud->group()->refresh('groupId1', 'newGroupName');
		echo "refresh    ";
		print_r($result);
		echo "\n";
	}
	
	public function joingroup(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 将用户加入指定群组，用户将可以收到该群的消息，同一用户最多可加入 500 个群，每个群最大至 3000 人。
		$result = $RongCloud->group()->join(["userId2","userid3","userId4"], 'groupId1', 'TestGroup');
		echo "join    ";
		print_r($result);
		echo "\n";
	}
	
	public function querygroupuser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 查询群成员方法
		$result = $RongCloud->group()->queryUser('groupId1');
		echo "queryUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function quitgroup(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 退出群组方法（将用户从群中移除，不再接收该群组的消息.）
		$result = $RongCloud->group()->quit(["userId2","userid3","userId4"], 'TestGroup');
		echo "quit    ";
		print_r($result);
		echo "\n";
	}
	
	public function addgroupuser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 添加禁言群成员方法（在 App 中如果不想让某一用户在群中发言时，可将此用户在群组中禁言，被禁言用户可以接收查看群组中用户聊天信息，但不能发送消息。）
		$result = $RongCloud->group()->addGagUser('userId1', 'groupId1', '1');
		echo "addGagUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function lisgaguser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 查询被禁言群成员方法
		$result = $RongCloud->group()->lisGagUser('groupId1');
		echo "lisGagUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function rollbackgroupuser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 移除禁言群成员方法
		$result = $RongCloud->group()->rollBackGagUser(["userId2","userid3","userId4"], 'groupId1');
		echo "rollBackGagUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function dismissgroup(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 解散群组方法。（将该群解散，所有用户都无法再接收该群的消息。）
		$result = $RongCloud->group()->dismiss('userId1', 'groupId1');
		echo "dismiss    ";
		print_r($result);
		echo "\n";
	}
	
	
	public function createchat(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		echo ("\n***************** chatroom **************\n");
		// 创建聊天室方法
		$chatRoomInfo['chatroomId1'] = 'chatroomInfo1';
		$chatRoomInfo['chatroomId2'] = 'chatroomInfo2';
		$chatRoomInfo['chatroomId3'] = 'chatroomInfo3';
		$result = $RongCloud->chatroom()->create($chatRoomInfo);
		echo "create    ";
		print_r($result);
		echo "\n";
	}
	
	public function joinchat(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 加入聊天室方法
		$result = $RongCloud->chatroom()->join(["userId2","userid3","userId4"], 'chatroomId1');
		echo "join    ";
		print_r($result);
		echo "\n";
	}
	
	public function querychat(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 查询聊天室信息方法
		$result = $RongCloud->chatroom()->query(["chatroomId1","chatroomId2","chatroomId3"]);
		echo "query    ";
		print_r($result);
		echo "\n";
	}
	
	public function queryuser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 查询聊天室内用户方法
		$result = $RongCloud->chatroom()->queryUser('chatroomId1', '500', '2');
		echo "queryUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function stopdistributionmsg(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 聊天室消息停止分发方法（可实现控制对聊天室中消息是否进行分发，停止分发后聊天室中用户发送的消息，融云服务端不会再将消息发送给聊天室中其他用户。）
		$result = $RongCloud->chatroom()->stopDistributionMessage('chatroomId1');
		echo "stopDistributionMessage    ";
		print_r($result);
		echo "\n";
	}
	
	public function resumedistributionmsg(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 聊天室消息恢复分发方法
		$result = $RongCloud->chatroom()->resumeDistributionMessage('chatroomId1');
		echo "resumeDistributionMessage    ";
		print_r($result);
		echo "\n";
	}
	
	public function addgaguser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 添加禁言聊天室成员方法（在 App 中如果不想让某一用户在聊天室中发言时，可将此用户在聊天室中禁言，被禁言用户可以接收查看聊天室中用户聊天信息，但不能发送消息.）
		$result = $RongCloud->chatroom()->addGagUser('userId1', 'chatroomId1', '1');
		echo "addGagUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function listgaguser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 查询被禁言聊天室成员方法
		$result = $RongCloud->chatroom()->ListGagUser('chatroomId1');
		echo "ListGagUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function rollbackgaguser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 移除禁言聊天室成员方法
		$result = $RongCloud->chatroom()->rollbackGagUser('userId1', 'chatroomId1');
		echo "rollbackGagUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function addblockuser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 添加封禁聊天室成员方法
		$result = $RongCloud->chatroom()->addBlockUser('userId1', 'chatroomId1', '1');
		echo "addBlockUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function getListblockuser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 查询被封禁聊天室成员方法
		$result = $RongCloud->chatroom()->getListBlockUser('chatroomId1');
		echo "getListBlockUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function rollbackblockuser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 移除封禁聊天室成员方法
		$result = $RongCloud->chatroom()->rollbackBlockUser('userId1', 'chatroomId1');
		echo "rollbackBlockUser    ";
		print_r($result);
		echo "\n";
	}
	
	public function addpriority(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 添加聊天室消息优先级方法
		$result = $RongCloud->chatroom()->addPriority(["RC:VcMsg","RC:ImgTextMsg","RC:ImgMsg"]);
		echo "addPriority    ";
		print_r($result);
		echo "\n";
	}
	
	public function destroychat(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 销毁聊天室方法
		$result = $RongCloud->chatroom()->destroy(["chatroomId","chatroomId1","chatroomId2"]);
		echo "destroy    ";
		print_r($result);
		echo "\n";
	}
	
	public function addwhitelistuser(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 添加聊天室白名单成员方法
		$result = $RongCloud->chatroom()->addWhiteListUser('chatroomId', ["userId1","userId2","userId3","userId4","userId5"]);
		echo "addWhiteListUser    ";
		print_r($result);
		echo "\n";
	}
	
	
	public function setuserpushtag(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		echo ("\n***************** push **************\n");
		// 添加 Push 标签方法
		$result = $RongCloud->push()->setUserPushTag(file_get_contents($this->jsonPath.'UserTag.json'));
		echo "setUserPushTag    ";
		print_r($result);
		echo "\n";
	}
	
	public function broadcastpush(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 广播消息方法（fromuserid 和 message为null即为不落地的push）
		$result = $RongCloud->push()->broadcastPush(file_get_contents($this->jsonPath.'PushMessage.json'));
		echo "broadcastPush    ";
		print_r($result);
		echo "\n";
	}
	
	public function getimagecode(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		echo ("\n***************** SMS **************\n");
		// 获取图片验证码方法
		$result = $RongCloud->SMS()->getImageCode('app-key');
		echo "getImageCode    ";
		print_r($result);
		echo "\n";
	}
	
	
	public function sendcode(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 发送短信验证码方法。
		$result = $RongCloud->SMS()->sendCode('13500000000', 'dsfdsfd', '86', '1408706337', '1408706337');
		echo "sendCode    ";
		print_r($result);
		echo "\n";
	}
	
	public function verifycode(){
		import('rongyun.rongcloud');
		$RongCloud = new \RongCloud($this->appKey,$this->appSecret);
		// 验证码验证方法
		$result = $RongCloud->SMS()->verifyCode('2312312', '2312312');
		echo "verifyCode    ";
		print_r($result);
		echo "\n";
	}
	
	
	
	
	
	
	
	
	
	
	
}