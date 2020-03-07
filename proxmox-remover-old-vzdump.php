#!/usr/bin/php
<?php
$dir = "/img/dom0/dump";


error_reporting(0);

$num_in_day = 2;

$exec = "ls $dir | grep vma";
exec($exec,$reg);
//print_r($reg);

$preg = "/vzdump-qemu-(\d*?)-(.*?)\./sim";
foreach($reg as $line)
{
    unset($reg2);
    preg_match($preg,$line,$reg2);
//    print_r($reg2);
    $num = $reg2[1];
    $t = $reg2[2];
    $t = str_replace("-","",$t);
    $t = str_replace("_","",$t);
//    print $t."\n";;
    $t = strtotime($t);
//    print $t."\n";;
    $time = date("Y-m-d H:i:s",$t);
    $out[$num][$t] = $line;
$nums["files"]++;
}

//print_r($out);
$num_of_vm = count($out);
//print "count of vm = $num_of_vm\n";die;
foreach($out as $k=>$v2)
{
    krsort($v2);
    $nn = 0;
    $num = count($v2);
    foreach($v2 as $t=>$name)
    {
    $nn++;
	if($nn > $num_in_day && $nn != ($num-1))
	{
	    $del[++$nn2] = $name;
	$last_nn = $nn;
	}
    }
    
}
//print_r($del);
$num = count($del);
$nn = 0;
foreach($del as $v)
{
$nn ++;
//    $exec_mas[] = "mv $dir/$v $dir/del/$v";
    $exec_mas[] = "echo \"now remove: $v ($nn of $num)\"";
    $exec_mas[] = "rm $dir/$v";
    $exec_mas[] = "";
}
//print_r($exec_mas);
$txt = implode("\n",$exec_mas);
$f = __FILE__.".sh";
file_put_contents($f,$txt);

//print_r($nums);
print "Found $nums[files] files\n";
$num = count($del);
print "Need remove ".$num." files";
if($num)
{
    print "\tFor remove action: sh ".basename($f)."\n";
}
print "\n";
?>