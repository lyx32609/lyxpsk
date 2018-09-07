<?php 
namespace app\index\model;

use think\Model;

class NewsComment extends Model
{
	protected $table = 'pxsk_news_comment';
	protected $autoWriteTimestamp = true;
}