<?php
$ops = $this -> get_options();
$errors = $this -> getErrors();
$update = $this -> getUpdate();
$notification_message = @$ops->notification_message;
if(empty($notification_message)){
	$notification_message = "ブログを更新しました。 %title% %url%";
}
$notification_embed = @$ops->notification_embed;
$notification_type_post = @$ops->notification_type_post;
$notification_type_page = @$ops->notification_type_page;
?>

<div class="wrap">
	<h2>
		notify
		<small> &nbsp;&nbsp;for WordPress &nbsp;&nbsp; version<?php echo $this->getVersion(); ?></small>
	</h2>

	<?php if(!empty($errors)){ ?>
		<?php foreach ($errors as $key => $value) { ?>
	<div id='<?php echo $key ?>' class='error settings-error'>
		<p><strong><?php echo $value ?></strong></p>
	</div>
		<?php } ?>
	<?php } ?>

	<?php if($update){ ?>
	<div id='setting-error-settings_updated' class='updated settings-error'>
		<p><strong>設定を保存しました。</strong></p>
	</div>
	<?php } ?>

	<p>
		記事を公開した場合にnotifyから通知を送ることができます。
	</p>

	<form action="" method="POST" class="form-horizontal">
		<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="notification_token">通知のtoken</label></th>
				<td>
					<input name="notification_token" type="text" id="notification_token"
					value="<?php echo @$ops->notification_token ?>" class="regular-text">
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row"><label for="notification_token">通知のタイプ</label></th>
				<td>
					<fieldset>
					<legend class="screen-reader-text"><span>整形</span></legend>
					<label for="notification_type_post">
						<input name="notification_type_post" type="checkbox" id="notification_type_post" value="1" 
						<?php if($notification_type_post) {echo 'checked="checked"'; } ?>>
						投稿
					</label><br />
					<label for="notification_type_page">
						<input name="notification_type_page" type="checkbox" id="notification_type_page" value="1" 
						<?php if($notification_type_post) {echo 'checked="checked"'; } ?>>
						固定ページ
					</label>
					</fieldset>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row"><label for="notification_message">通知メッセージ</label></th>
				<td>
					<textarea name="notification_message" id="app_notification_message"
					 class="large-text code" rows="3"><?php echo $notification_message ?></textarea>
					 
					 <p class="description">変数は%%で囲んでください。使用できる変数はtitle(記事のタイトル) とurl(記事へのショートリンク)となっております。</p>
				</td>
			</tr>
			
		</tbody>
		</table>
		<p class="submit"><input type="submit" name="notify_options_submit" id="submit" class="button button-primary" value="変更を保存"></p>
	</form>
	
	<table class="form-table">
		<tbody>
			<?php if(!empty($notification_embed)){ ?>
			<tr valign="top">
				<th scope="row"><label for="notification_message">埋め込みコード</label></th>
				<td>
					<textarea name="notification_embed" id="" class="large-text code" rows="7"><?php echo $notification_embed ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="notification_message">プレビュー</label></th>
				<td>
					<?php echo $notification_embed ?>
				</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
</div>

