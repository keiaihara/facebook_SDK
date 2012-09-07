<?php $data = $_POST;?>
<form action="" method="post" id="form">
    <input type="hidden" value="<?=$data["id"]?>" name="id" size="10" class="required" />
    <dl>
        <dt id="postDay">【投稿日】　　<span>例） 2012-03-02</span></dt>
        <dd><input type="text" value="<?=$data["post_date"]?>" name="post_date" size="10" class="required" /></dd>
        <dt>【投稿内容】<span>※1回目</span></dt>
        <dd>
            <textarea id="inquiry" name="text1" rows="5" cols="120" ><?=$data["text1"]?></textarea>
        </dd>
        <dt>【複数画像投稿の有無】<span>※必須</span></dt>
        <dd>
            <label for="ari"><input type="radio" value="1" name="images1"  checked="<?if($data["images1"])echo "checked";?>">あり</label>
            <label for="nashi"><input type="radio" value="0" name="images1"  checked="<?if(!$data["images1"])echo "checked";?>">なし</label>
        </dd>
        <dt>【画像ファイル名】</dt>
        <dd><input type="text" value="<?=$data["img1"]?>" name="img1" size="50"/></dd>
        <dt>【投稿内容】<span>※2回目</span></dt>
        <dd>
            <textarea id="inquiry" name="text2"rows="5" cols="120"><?=$data["text2"]?></textarea>
        </dd>
        <dt>【複数画像投稿の有無】<span>※必須</span></dt>
        <dd>
            <label for="ari"><input type="radio" value="1" name="images2"  checked="<?if($data["images2"])echo "checked";?>">あり</label>
            <label for="nashi"><input type="radio" value="0" name="images2"  checked="<?if(!$data["images2"])echo "checked";?>">なし</label>
        </dd>
        <dt>【画像ファイル名】</dt>
        <dd><input type="text" value="<?=$data["img2"]?>" name="img2" size="50"/></dd>
        
    </dl>
    <p id=button>
    	<input type="submit" value="保存" style="width:80px;height:35px;"/>
    	<input type="button" name="cancel" value="キャンセル" style="width:80px;height:35px;"/>
    </p>
</form>
