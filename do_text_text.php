<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
/**************************************************************
"Learning with Texts" (LWT) is free and unencumbered software 
released into the PUBLIC DOMAIN.

Anyone is free to copy, modify, publish, use, compile, sell, or
distribute this software, either in source code form or as a
compiled binary, for any purpose, commercial or non-commercial,
and by any means.

In jurisdictions that recognize copyright laws, the author or
authors of this software dedicate any and all copyright
interest in the software to the public domain. We make this
dedication for the benefit of the public at large and to the 
detriment of our heirs and successors. We intend this 
dedication to be an overt act of relinquishment in perpetuity
of all present and future rights to this software under
copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE 
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE
AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS BE LIABLE 
FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN 
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN 
THE SOFTWARE.

For more information, please refer to [http://unlicense.org/].
***************************************************************/

/**************************************************************
Call: do_text_text.php?text=[textid]
Show text header frame
***************************************************************/
$before = microtime(true);
require_once( 'settings.inc.php' );
require_once( 'connect.inc.php' );
require_once( 'dbutils.inc.php' );
require_once( 'utilities.inc.php' );

$headerUpdate="";
$sql = 'select TxID,TxLgID, TxTitle, TxAnnotatedText,TxText from ' . $tbpref . 'texts where TxID = ' . $_REQUEST['text'];
if(isset($_GET['next'])){
$sql = 'select TxID,TxLgID, TxTitle, TxAnnotatedText,TxText from ' . $tbpref . 'texts where TxID  = IFNULL((select min(TxID) from ' . $tbpref . 'texts where TxID > ' . $_REQUEST['text'].' AND TxLgID='.$_REQUEST['lang'].'),' . $_REQUEST['text'].')';
}elseif(isset($_GET['previous'])){
$sql = 'select TxID,TxLgID, TxTitle, TxAnnotatedText,TxText from ' . $tbpref . 'texts where TxID =IFNULL((select max(TxID) from ' . $tbpref . 'texts where TxID < ' . $_REQUEST['text'].' AND TxLgID='.$_REQUEST['lang'].'),' . $_REQUEST['text'].')';
}
$res = do_mysql_query($sql);
$record = mysql_fetch_assoc($res);
$TxtId=$record['TxID'];
$title = $record['TxTitle'];
$langid = $record['TxLgID'];
$textFull="";
$textIntact=$record['TxText'];
$ann = $record['TxAnnotatedText'];
$ann_exists = (strlen($ann) > 0);
mysql_free_result($res);

if(isset($_GET['next']) || isset($_GET['previous'])){
$headerUpdate="window.parent.frames['h'].src='do_text_text.php?text=$TxtId';";
}

pagestart_nobody(tohtml($title));

$sql = 'select splitSize, LgSplitEachChar, LgRegexpWordCharacters, LgName, LgGoogleTranslateURI, LgTextSize, LgRemoveSpaces, LgRightToLeft, LgID  from ' . $tbpref . 'languages where LgID = ' . $langid;
$res = do_mysql_query($sql);
$record = mysql_fetch_assoc($res);
$wb3 = isset($record['LgGoogleTranslateURI']) ? $record['LgGoogleTranslateURI'] : "";
$termchar=$record['LgRegexpWordCharacters'];
$textsize = $record['LgTextSize'];
$SplitAll=($record['LgSplitEachChar']==0)?false:true;
$NoSpaces=($record['LgRemoveSpaces']==0)?false:true;
$removeSpaces = $record['LgRemoveSpaces'];
$rtlScript = $record['LgRightToLeft'];
$languageName=$record['LgName'];
$splitMax=$record['splitSize'];
mysql_free_result($res);

$sql = 'select name,URI from ' . $tbpref . 'dictionaries where languagesLgID = ' . $langid;
$res = do_mysql_query($sql);
$dictionaries=array();
$jsDictsString="DICTS=[";
while($record=mysql_fetch_assoc($res)){
array_push($dictionaries,[$record['name'],$record['URI']]);
$jsDictsString=$jsDictsString.'["'."$record[name]".'","'."$record[URI]".'"],';
}
$jsDictsString=$jsDictsString."];";
mysql_free_result($res);

$sql = 'SELECT WoID, WoText, WoStatus, WoTranslation, WoRomanization from ' . $tbpref . 'words WHERE WoLgID = ' . $langid;
$res = do_mysql_query($sql);
$wordList=array();
$jsWordList="var dictionary = {";
while($record=mysql_fetch_assoc($res)){
$wordList[mb_strtolower($record['WoText'], 'UTF-8')]=["id"=>$record['WoID'],"text"=>$record['WoText'],"tlower"=>mb_strtolower($record['WoText'], 'UTF-8'),"status"=>$record['WoStatus'],"translation"=>$record['WoTranslation'],"romanization"=>$record['WoRomanization']];
$jsWordList.='"'.$record['WoText'].'": [ "'.$record['WoID'].'", "'.$record['WoText'].'", "'.mb_strtolower($record['WoText'], 'UTF-8').'"],';
}
$jsWordList.="};";
mysql_free_result($res);

$showAll = getSettingZeroOrOne('showallwords',1);

$regex="/([^$termchar]{1,})/";
if($SplitAll){
$regex="/([$termchar]{1})/";
}
?>
<script type="text/javascript">
//<![CDATA[
ANN_ARRAY = <?php echo annotation_to_json($ann); ?>;
TEXTPOS = -1;
OPENED = 0;
REGEX=<?php echo $regex; ?>;
<?php echo $jsDictsString; ?>;
<?php echo $headerUpdate; ?>;
<?php echo $jsWordList ?>;


WBLINK3 = '<?php echo $wb3; ?>';

RTL = <?php echo $rtlScript; ?>;
TID = '<?php echo $_REQUEST['text']; ?>';
ADDFILTER = '<?php echo makeStatusClassFilter(getSettingWithDefault('set-text-visit-statuses-via-key')); ?>';
$(document).ready( function() {
	$('.word').each(word_each_do_text_text);
	$('.mword').each(mword_each_do_text_text);
	$('.word').click(word_click_event_do_text_text);
	$('.mword').click(mword_click_event_do_text_text);
	$('.word').dblclick(word_dblclick_event_do_text_text);
	$('.mword').dblclick(word_dblclick_event_do_text_text);
	$(document).keydown(keydown_event_do_text_text);
});
//]]>
</script>
<?php

echo '<div id="navigate-top" style="text-align:left;font-size:150%;"><div><a href="do_text_text.php?text='.$TxtId.'&previous=1&lang='.$langid.'" title="Previous text in '.$languageName.'">&#8678;&nbsp;&nbsp;</a><span>'.$title.'</span><a href="do_text_text.php?text='.$TxtId.'&next=1&lang='.$langid.'" title="Next text in '.$languageName.'">&nbsp;&nbsp;&#8680;</a></div></div>'.
'<div id="thetext" ' .  ($rtlScript ? 'dir="rtl"' : '') . ' ><p id="container" style="' . ($removeSpaces ? 'word-break:break-all;' : '') . 
'font-size:' . $textsize . '%;line-height: 1.4; margin-bottom: 10px;">';

$currcharcount = 0;	

$sql = 'SELECT SeSplit, SeText, SeID from ' . $tbpref . 'sentences WHERE SeLgID = ' . $langid. ' AND SeTxId='.$TxtId.' ORDER BY SeID';
$sentences = do_mysql_query($sql);
		$sentNumber=0;
		$ordId=1;
		$sentencesD=array();
		$sentencesS=array();
		$Unknown=array();
		$WordsAll=array();
		$FrasesAll=array();
while($sentence=mysql_fetch_assoc($sentences)){
$tempWordsets=array();
$sentenceSplit=$sentence['SeSplit'];

$tempWords=explode(';',$sentenceSplit);
//var_dump($tempWords);
$tempWords2=array();
$tempBuild="";
foreach($tempWords as $word){
if(preg_split('/([^' . $termchar . ']{1,})/u', $word)[0]==""){
$tempBuild.=$word;
}else{
if($tempBuild!=""){
$tempWords2[]="*".$tempBuild;
$tempBuild="";
}
$tempWords2[]=$word;
}
}
if($tempBuild!=""){$tempWords2[]="*".$tempBuild;}
$tempWords=$tempWords2;
//var_dump($tempWords);
$l = count($tempWords);	
			for ($i=0; $i<$l; $i++) {
			if($tempWords[$i][0]=="*"){
			$tempWordsets[$ordId]=[substr($tempWords[$i],1).' ',1,$ordId,$sentence['SeID'],1,substr($tempWords[$i],1).' ',$count2]; $ordId++;
			continue;}
			$frase="";
			for ($j=1; 0<=$l-$i-$j; $j++) {
			if($tempWords[$i+$j][0]=="*" ){continue;}
			$frase="";
			$past="";
			$count1=0;
			$count2=0;
			$length=0;
				for ($k=0; 0<=$l-$i-$j-$k && $k<=$splitMax; $k++) {
				$length++;
				if($tempWords[$i+$k][0]=="*"){$count2++;$past=substr($tempWords[$i+$k],1);continue;}
					$count1++;
					if($past!=""){$frase.=$past;$past="";}
					$frase.=$tempWords[$i+$k];
				}
				if(array_key_exists(mb_strtolower($frase, 'UTF-8'),$wordList)){
				$tempWordsets[$ordId]=[$frase,$count1,$ordId,$sentence['SeID'],0,$tempWords[$i],$count2];
				$i+=$length-1;
				if($length>1){
				$FrasesAll[]=mb_strtolower($frase, 'UTF-8');
				}else{
				$WordsAll[]=mb_strtolower($frase, 'UTF-8');
				}
				for($Win=0;$Win<$length-1;$Win++){
				$ordId++;
				if($SplitAll){
				$ghet=0;
				}else{
				$ghet=2;
				}
				$valuey=$tempWords[($i)+$Win-$ghet];
				if($valuey[0]=="*"){
				$valuexx=substr($tempWords[($i)+$Win-$ghet],1);
				$tempWordsets[$ordId]=[$valuexx,1,$ordId,$sentence['SeID'],1,$valuexx,1];
				}else{
				$valuexy=$tempWords[($i)+$Win];
				if($valuexy[0]=="*"){
				$tempWordsets[$ordId]=[substr($valuexy,1),1,$ordId,$sentence['SeID'],1,substr($valuexy,1),1];
				}else{
				$tempWordsets[$ordId]=[$valuexy,1,$ordId,$sentence['SeID'],0,$valuexy,1];
				}
				}
				}
				}else{
				if($length==1){
				if($frase[0]=="*"){}
				else{
				$Unknown[]=mb_strtolower($frase, 'UTF-8');
				$WordsAll[]=mb_strtolower($frase, 'UTF-8');
				$tempWordsets[$ordId]=[$frase,$count1,$ordId,$sentence['SeID'],0,$tempWords[$i],$count2];}}
				}
			}
			$ordId++;
			}
		$sentencesD[trim($sentence['SeText'])]=$tempWordsets;
		$sentencesS[]=trim($sentence['SeText']);
			$sentNumber += 1;
			}
$Unknown=array_unique($Unknown);
$WordsAll=array_unique($WordsAll);
$FrasesAll=array_unique($FrasesAll);
$Allc=count($WordsAll);
$Allu=count($Unknown);
$Allf=count($FrasesAll);
$Unkc=$Allc-$Allu-$Allf;
runsql('UPDATE ' . $tbpref . 'texts SET words="'.$Allc.'", words_saved="'.($Unkc).'", frases_saved="'.($Allf).'" WHERE TxID='.$TxtId);
$countx=count($sentencesS);
for($ii=0;$ii<$countx;$ii++){
$value=$sentencesS[$ii];
$wordstouse=$sentencesD[$value];
$resultHtml="";
$lastOrder=0;
$ignoreId=0;
$furtherIgnore=0;
foreach($wordstouse as $a=>$w1){
$record=[
			'TiText' =>$w1[0],
			'TiTextBase' =>$w1[5],
			'TiSentId' =>$w1[3],
			'Code' =>$w1[1],
			'TiOrder' =>$w1[2],
			'TiIsNotWord' =>$w1[4],
			'TiTextLength' =>strlen($w1[0])
		];
if($lastOrder==0){
$resultHtml.='<span id="WORD-'.$record['TiOrder'].'" data_word_id="'.$record['TiOrder'].'" data_sentence_id="'.$record['TiSentId'].'" data_language_id="'.$langid.'" data_word="'.$record['TiTextBase'].'">';
$lastOrder=$record['TiOrder'];
}elseif($lastOrder!=$record['TiOrder']){
$resultHtml.='</span>';
$resultHtml.='<span id="WORD-'.$record['TiOrder'].'" data_word_id="'.$record['TiOrder'].'" data_sentence_id="'.$record['TiSentId'].'" data_language_id="'.$langid.'" data_word="'.$record['TiTextBase'].'">';
$lastOrder=$record['TiOrder'];
}
$thisWord=$wordList[mb_strtolower($record['TiText'], 'UTF-8')];

if((($thisWord==null && $record['Code']==1) || $thisWord!=null) && ($ignoreId!=$record['TiOrder'])){$ignoreId=$record['TiOrder'];}else{continue;}
$recordCode=$record['Code'];
$recordTiOrder=$record['TiOrder'];
	$actcode = $recordCode + 0;
	$spanid = 'ID-' . $recordTiOrder . '-' . $actcode;
				
	if ($record['TiIsNotWord'] != 0) {  // NOT A TERM
	if($furtherIgnore!=0) {continue;}
		$resultHtml.= '<span id="' . $spanid . '" class="" >' . 
			str_replace(
			"¶",
			'<br />',
			tohtml($record['TiText'])) . '</span>';
	}  // $record['TiIsNotWord'] != 0  --  NOT A TERM
	else {   // $record['TiIsNotWord'] == 0  -- A TERM
	if($furtherIgnore!=0) {$furtherIgnore--;continue;}
		if ($actcode > 1) {   // A MULTIWORD FOUND
			if ($thisWord!=null) {  // MULTIWORD FOUND - DISPLAY (Status 1-5, display)
				if (! $showAll) {
					if ($hideuntil == -1) {
						$hideuntil = $recordTiOrder + ($recordCode - 1) * 2;
					}
				}				
				$resultHtml.='<span id="'.$spanid.'" class="click mword '.($showAll ? 'mwsty' : 'wsty').' order'.$recordTiOrder.' word'.$thisWord['id'].' status'.$thisWord['status'].' TERM'.strToClassName(mb_strtolower($record['TiText'], 'UTF-8')).'" data_pos="'.$currcharcount.'" data_order="'.$recordTiOrder.'" data_wid="'.$thisWord['id'].'" data_trans="'.tohtml(repl_tab_nl2($thisWord['translation']) . getWordTagList($thisWord['id'],' ',1,0)).'" data_rom="'.tohtml($thisWord['romanization']).'" data_status="'.$thisWord['status'].'"  data_code="'.$recordCode.'" onmousedown="text_onmousedown_event_do_text_text(this,'.$TxtId.')">'.($showAll ? ('&nbsp;' . $recordCode . '&nbsp;') : tohtml($record['TiText'])).'</span>';
			$furtherIgnore=$record['Code']-1;
			}
			else {  // MULTIWORD PLACEHOLDER - NO DISPLAY 
				$resultHtml.='<span id="'.$spanid.'" class="click mword '.($showAll ? 'mwsty' : 'wsty').'   '.'order'. $recordTiOrder.'  TERM'.strToClassName(mb_strtolower($record['TiText'], 'UTF-8')).'" data_pos="'.$currcharcount.'" data_order="'.$recordTiOrder.'" data_wid="" data_trans="" data_rom="" data_status="" data_code="'.$recordCode.'" onmousedown="text_onmousedown_event_do_text_text(this,'.$TxtId.')">'.($showAll ? ('&nbsp;' . $recordCode. '&nbsp;') : tohtml($record['TiText'])).'</span>';	
			}   // MULTIWORD PLACEHOLDER - NO DISPLAY 
		} // ($actcode > 1) -- A MULTIWORD FOUND
		else {  // ($actcode == 1)  -- A WORD FOUND
			if ($thisWord!=null) {  // WORD FOUND STATUS 1-5,98,99
				$resultHtml.='<span id="'.$spanid.'" class="click word wsty   word'. $thisWord['id'].'   status'. $thisWord['status'].'  TERM'.strToClassName(mb_strtolower($record['TiText'], 'UTF-8')).'" data_pos="'.$currcharcount.'" data_order="'.$recordTiOrder.'" data_wid="'.$thisWord['id'].'" data_trans="'.tohtml($thisWord['translation']). getWordTagList($thisWord['id'],' ',1,0).'" data_rom="'.tohtml($thisWord['romanization']).'" data_status="'.$thisWord['status'].'" onmousedown="text_onmousedown_event_do_text_text(this,'.$TxtId.')">'.tohtml($record['TiText']).'</span>';	
			}   // WORD FOUND STATUS 1-5,98,99
			else {    // NOT A WORD AND NOT A MULTIWORD FOUND - STATUS 0
				$resultHtml.='<span id="'.$spanid.'" class="click word wsty status0 TERM'.strToClassName(mb_strtolower($record['TiText'], 'UTF-8')).'" data_pos="'.$currcharcount.'" data_order="'.$recordTiOrder.'" data_trans="" data_rom="" data_status="0" data_wid="" onmousedown="text_onmousedown_event_do_text_text(this,'.$TxtId.')">'.tohtml($record['TiText']).'</span>';	
			}  // NOT A WORD AND NOT A MULTIWORD FOUND - STATUS 0
		}  // ($actcode == 1)  -- A WORD FOUND
	} // $record['TiIsNotWord'] == 0  -- A TERM
	
	if ($actcode == 1) $currcharcount += $record['TiTextLength']; 
}
$resultHtml.='</span>';
if($SplitAll){
$textFull.=$resultHtml;
}else{
$textFull.=$resultHtml;
}
}
echo '<div id="text-container" data_language_split="' . $SplitAll . '" style="font-size:' . $textsize . '%;line-height: 1.4;">'.$textFull.'</div>';

mysql_free_result($res);
echo '<span id="totalcharcount" class="hide">' . $currcharcount . '</span></p><p style="font-size:' . $textsize . '%;line-height: 1.4; margin-bottom: 300px;">&nbsp;</p></div></br>';

pageend();
?>