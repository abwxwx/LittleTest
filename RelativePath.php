<?php
// 写一个函数，算出两个文件的相对路径
  // 如 $a = '/a/b/c/d/e.php';
  // $b = '/a/b/12/34/c.php';
  // 计算出 $b 相对于 $a 的相对路径应该是 ../../c/d将()添上
  
$a = '/a/b/c/d/1/e.php';
$b = '/a/b/12/34/c.php';

echo getRelativePath($a, $b);

function getRelativePath($a, $b)
{
	$a2array = explode('/', $a);
	$b2array = explode('/', $b);
	$anum = count($a2array);
	$bnum = count($b2array);
	
	$maxCount = 0;
	$i = 0;
	
	//要求$b相对于$a的相对路径，$b和$a的路径树的第一级必需一样，否则没有相对路径
	if($a2array[0] != $b2array[0])
	{
		return false;
	}

	while($i < $anum)
	{
		if($a2array[$i] == $b2array[$i])
		{
			$maxCount++;
		}
		else
		{
			break;
		}
		$i++;
	}
	
	$strRelativePath = str_repeat('../', ($bnum-$maxCount));
	while($i < $anum-1)
	{
		$strRelativePath .= $a2array[$i];
		$strRelativePath .= '/';
		$i++;
	}
	$strRelativePath .= $a2array[$i];
	return $strRelativePath;
}

