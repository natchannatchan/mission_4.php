<?php

//POSTで受け取った値を代入

	$name = $_POST['name'];			//名前
	$comment = $_POST['comment']; 		//コメント
	$delete = $_POST['delete'];		//削除番号
	$hensyuu = $_POST['hensyuu'];		//編集番号
	$hensyuu2 = $_POST['hensyuu2'];		//編集番号再表示

	$botan1 = $_POST['botan1'];		//送信ボタン
	$botan2 = $_POST['botan2'];		//削除ボタン
	$botan3 = $_POST['botan3'];		//編集ボタン

	$password1 = $_POST['password1'];	//送信のパスワード
	$password2 = $_POST['password2'];	//削除のパスワード
	$password3 = $_POST['password3'];	//編集のパスワード



//(3-1)データベースへ接続する
	$dsn='データベース名';
	$user='ユーザー名';
	$password='パスワード';
	$pdo=new PDO($dsn,$user,$password);



//(3-2)データベース内にテーブルを作成する
$sql="CREATE TABLE mission4"			//テーブル作成のSQLを作成
."("
."id0 INT,"
."name0 TEXT,"					//名前
."comment0 TEXT,"				//コメント
."time0 TEXT,"					//時間
."password0 TEXT"				//パスワード
.");";
$stmt = $pdo->query($sql); 			//SQLを実行



//通常の書き込み
if(isset($botan1)){					//送信ボタンが押される時
if(!empty($name)){					//名前欄が空でない時
if(!empty($comment)){					//コメント欄が空でない時
if(empty($hensyuu2)){					//編集番号再表示欄が空な時
if(!empty($password1)){					//送信のパスワード欄が空でない時

$sql='SELECT*FROM mission4';
$results=$pdo->query($sql);
foreach ($results as $row){				//$resultsを$rowに１行ずつ代入（行数分繰り返す）
}

$date = date("Y/m/d H:i:s");       			//投稿日時
$num = $row['id0'];                                     // 最後の投稿番号を代入
$num++;							// 最後の投稿番号にプラス１した

//(3-5)データを入力する
$sql=$pdo->prepare("INSERT INTO mission4(id0,name0,comment0,time0,password0)VALUES(:id0,:name0,:comment0,:time0,:password0)");
$sql->bindParam(':id0',$idx,PDO::PARAM_STR);
$sql->bindParam(':name0',$namex,PDO::PARAM_STR);
$sql->bindParam(':comment0',$commentx,PDO::PARAM_STR);
$sql->bindParam(':time0',$timex,PDO::PARAM_STR);
$sql->bindParam(':password0',$passwordx,PDO::PARAM_STR);
$idx=$num;
$namex=$name;
$commentx=$comment;
$timex=$date;
$passwordx=$password1;
$sql->execute();

}}}}}



//削除ボタン
if(isset($botan2)){						//削除ボタンが押された時
if(!empty($delete)){						//削除対象番号欄が空でない時
if(!empty($password2)){						//削除のパスワード欄が空でない時

$sql='SELECT*FROM mission4';
$results=$pdo->query($sql);
foreach ($results as $row){					//$resultsを$rowに１行ずつ代入（行数分繰り返す）

	if($row['id0'] == $delete){				//削除したいコメントの番号と削除対象番号欄の番号が一致した時
		$deletepassword = $row['password0'];		//その番号のパスワードを代入する
	}

}

if($password2 == $deletepassword){				//削除のパスワード欄と消したいコメントのパスワードが一致していたら
	//データを削除する
	//$id=$delete;						//IDに消したい行の数字を入れる
	//$sql="delete from mission4 where id0=$id";   		//入れた数字の行を消す
	//$result=$pdo->query($sql);

	//データを削除する代わりに番号以外空欄にする
	//データを編集する
	$id=$delete;
	$sql="update mission4 set name0='' , comment0='' , time0='' , password0='' where id0 = $id";
	$result=$pdo->query($sql);

}

else{								//削除のパスワード欄と元のパスワードが一致していなかったら
	echo "パスワードが違います！！！<br><br>";		//コメントを表示させる
}

}}}



//編集番号を入力して編集ボタンが押されたら
if(isset($botan3)){							//編集ボタンが押された時
if(!empty($hensyuu)){							//編集対象番号欄が空でない時
if(!empty($password3)){							//編集のパスワード欄が空でない時

$sql='SELECT*FROM mission4';
$results=$pdo->query($sql);
foreach ($results as $row){						//$resultsを$rowに１行ずつ代入（行数分繰り返す）

	if($hensyuu == $row['id0']){					//編集対象番号欄と編集したいコメントの番号が一致している行があったら

		if($password3 == $row['password0']){			//編集のパスワード欄と編集したいコメントのパスワードが一致していたら
			$name1=$row['name0'];				//名前を代入（編集する名前）
			$comment1=$row['comment0'];			//コメントを代入（編集するコメント）
		}
		else{							//編集のパスワード欄と編集したいコメントのパスワードが一致していなかったら
			echo "パスワードが違います！！！<br><br>";	//コメントを表示させる
		}

	}

}

}}}



//編集ボタンを押して編集した後送信ボタンを押した時
if(isset($botan1)){					//送信ボタンが押される時
if(!empty($name)){					//名前欄が空でない時
if(!empty($comment)){					//コメント欄が空でない時
if(!empty($hensyuu2)){					//編集番号再表示欄が空でない時

	//データを編集する
	$id=$hensyuu2;
	$nm=$name;
	$kome=$comment;
	$zikan = date("Y/m/d H:i:s");       		//投稿日時
	$pass=$password1;
	$sql="update mission4 set name0='$nm' , comment0='$kome' , time0='$zikan' , password0='$pass' where id0 = $id";
	$result=$pdo->query($sql);

}}}}

?>



<form method="post"action="mission_4.php">					<!-データの送信方法、サーバの送信先->

<!-送信関連->
   <label for="name">名前:</label>						<!-フォーム部品とラベルを関連付ける->
   <input type="text" name="name" value=<?php echo "$name1"; ?>><br><br>	<!-１行テキストを入力するフィールド、フォームの名前を指定、編集する名前を表示->

   <label for="comment">コメント:</label>					<!-フォーム部品とラベルを関連付ける->
   <input type="text" name="comment" value=<?php echo "$comment1"; ?>><br><br>	<!-１行テキストを入力するフィールド、フォームの名前を指定、編集するコメントを表示->

   <label for="delete">パスワード:</label>					<!-フォーム部品とラベルを関連付ける->
   <input type="text" name="password1">						<!-１行テキストを入力するフィールド、フォームの名前を指定->

   <input type="submit" name="botan1" value="送信"><br><br><br>			<!-submit=実行ボタン、ボタンに表示させる文字->


<!-削除関連->
   <label for="delete">削除対象番号:</label>					<!-フォーム部品とラベルを関連付ける->
   <input type="text" name="delete"><br><br>					<!-１行テキストを入力するフィールド、フォームの名前を指定->

   <label for="delete">パスワード:</label>					<!-フォーム部品とラベルを関連付ける->
   <input type="text" name="password2">						<!-１行テキストを入力するフィールド、フォームの名前を指定->

   <input type="submit" name="botan2" value="削除"><br><br><br>			<!-submit=実行ボタン、ボタンに表示させる文字->


<!-編集関連->
   <label for="hensyuu">編集対象番号:</label>					<!-フォーム部品とラベルを関連付ける->
   <input type="text" name="hensyuu"><br><br>					<!-１行テキストを入力するフィールド、フォームの名前を指定->

   <label for="delete">パスワード:</label>					<!-フォーム部品とラベルを関連付ける->
   <input type="text" name="password3">						<!-１行テキストを入力するフィールド、フォームの名前を指定->

   <input type="submit" name="botan3" value="編集"><br><br>			<!-submit=実行ボタン、ボタンに表示させる文字->


<!-ブラウザ上では表示させないようにした編集番号再表示のフィールド>
   <input type="hidden" name="hensyuu2" value=<?php echo "$hensyuu"; ?>>	<!-１行テキストを入力するフィールドを隠す、フォームの名前を指定、編集する番号を表示->

</form>



<?php

//(3-6)入力したデータを表示する
echo "<hr>";						//線を引く
echo "（ わざとパスワードを表示しています ）<br><br>";
$sql='SELECT*FROM mission4';
$results=$pdo->query($sql);
foreach ($results as $row){				//$resultsを$rowに１行ずつ代入（行数分繰り返す）
//$rowの中にはテーブルのカラム名が入る
echo $row['id0'].',　';
echo $row['time0'].',　';
echo $row['name0'].',　';
echo $row['comment0'].',　';
echo "（ ";
echo $row['password0'].' ）<br>';
}



//(3-3)テーブルが作成できたか確認をする。
echo "<br>";
echo "<hr>";						//線を引く
echo "↓作成してあるテーブル<br>";

$sql='SHOW TABLES';					//テーブルの名前を表示させることをやる
$result = $pdo->query($sql);				//配列の形で取得する？
foreach($result as $row){				//$resultを$rowに１行ずつ代入
echo $row[0];						//結果としてテーブルの名前が表示された（１行のはずが配列）
echo '<br>';
}
echo "<hr>";						//線を引く



//(3-4)意図した内容のテーブルが作成されているか確認する
echo "<br>";
echo "<hr>";						//線を引く
echo "↓テーブルの中身<br>";

$sql ='SHOW CREATE TABLE mission4';
$result = $pdo -> query($sql);
foreach ($result as $row){				//$resultsを$rowに１行ずつ代入（行数分繰り返す）
print_r($row);
}
echo "<hr>";

?>
