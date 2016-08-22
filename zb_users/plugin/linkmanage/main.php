<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
    $zbp->ShowError(6);
    die();
}
if (!$zbp->CheckPlugin('linkmanage')) {
    $zbp->ShowError(48);
    die();
}
$Navs = linkmanageGetNav();
global $sysMenu;
$blogtitle = '链接编辑';
require $blogpath.'zb_system/admin/admin_header.php';
require $blogpath.'zb_system/admin/admin_top.php';

if (GetVars('del', 'GET')) {
    linkmanage_deleteNav(GetVars('del', 'GET'));
}

if (GetVars('creat', 'POST') == 'new') {
    linkmanage_creatNav(GetVars('id', 'POST'));
}
$menuID = GetVars('id');
if (isset($menuID) && $menuID == '') {
    unset($menuID);
}

?>
<script type="text/javascript" src="jquery.mjs.nestedSortable.js"></script>
<script type="text/javascript" src="js.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" />

<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?></div>
 <div class="SubMenu"><?php linkmanage_SubMenu(0);?></div>
  <div id="divMain2">

  	<div class="choose-menus">
		<label>选择要编辑的菜单：</label>
		<select id="select-menu-to-edit">
<?php
if (!isset($menuID)) {
    echo '<option value="">---请选择导航编辑---</option>';
}
foreach ($Navs['data'] as $key => $value) {
    $location = ($value['location'] == '') ? '未使用' : $value['location'];
    echo '<option value="'.$key.'">'.$value['name'].' ('.$location.')</option>';
}
?>
		</select>
		<span><input type="submit" class="button-secondary" value="选择" onclick="self.location='main.php?id='+$('#select-menu-to-edit option:selected').val(); "></span>
		<span>或 <a onclick="add_menu()">  创建新菜单</a></span>
	</div>
<?php if (isset($menuID)) {
    ?>
	<div id="nav-menus-frame">
		<div id="menu-settings">
			<div class="clear"></div>
			<div class="menu-settings-header">
				<p>常用链接</p>
			</div>
			<div id="accordion" class="accordion-container">
				  <div class="group">
				    <h3>文章</h3>
				    <div class="accordion-section-content">
					    <div class="input-control select">
							<select multiple="1" size="6" id="post">
								<?php linkmanage_get_link('post');
    ?>
							</select>
						</div>
						<p class="button-controls">
								<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="addsekectpartent(this,'post','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
				    </div>
				  </div>

				 <div class="group">
				    <h3>页面</h3>
				    <div class="accordion-section-content">
				    	<div class="input-control select">
							<select multiple="1" size="6" id="page">
								<?php linkmanage_get_link('page');
    ?>
							</select>
						</div>
						<p class="button-controls">
							<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="addsekectpartent(this,'page','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
					</div>
				  </div>

				  <div class="group">
				    <h3>分类</h3>
				    <div class="accordion-section-content">
					    <div class="input-control select">
							<select multiple="1" size="6" id="category">
								<?php linkmanage_get_link('category');
    ?>
							</select>
						</div>
						<p class="button-controls">
								<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="addsekectpartent(this,'category','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
				    </div>
				  </div>

				  <div class="group">
				    <h3>标签</h3>
				    <div class="accordion-section-content">
					    <div class="input-control select">
							<select multiple="1" size="6" id="tags">
								<?php linkmanage_get_link('tags');
    ?>
							</select>
						</div>
						<p class="button-controls">
								<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="addsekectpartent(this,'tags','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
				    </div>
				  </div>
			</div>
		</div>

		<div id="menu-management">
			<div id="menu-manage">
				<div class="menu-edit">
					<form id="menuName" method="POST">
					<input name="id" type="hidden" value="<?php echo $menuID;
    ?>">
					<div id="nav-menu-header">
						<div class="major-publishing-actions">
							<label class="menu-name-label howto open-label" for="menu-name">
								<span>菜单名称(<?php echo $Navs['data'][$menuID]['id'];
    ?>)：</span>
								<input name="MenuName" id="menu-name" type="text" class="menu-name regular-text menu-item-textbox" title="在此输入菜单名称" value="<?php echo $Navs['data'][$menuID]['name'];
    ?>">
							</label>
							<div class="publishing-action">
								<input type="submit" name="save_menu" id="save_menu_header" class="button button-primary menu-save" value="保存菜单" onclick="postsort();return false;">
							</div><!-- END .publishing-action -->
						</div><!-- END .major-publishing-actions -->
					</div>
					</form>
					<div id="menu-edit-body">
						<div id="menu-edit-body-content">
							<ol class="nav-menu ui-sortable">
							<?php
$html = '';
    $Menus = linkmanageGetMenu();
    $menuIDarray = json_decode($zbp->Config('linkmanage')->$menuID, true);
    foreach ($menuIDarray as $key => $value) {
        $menu = $Menus['ID'.$key];
        if ($value == 'null') {
            $html_tmp = '
			<li sid="menuItem_'.$menu['id'].'">
				<div class="menu-item-bar">
					<div class="menu-item-handle ui-sortable-handle">
						<span class="item-title"><span class="menu-item-title">'.$menu['title'].'</span> </span>
						<span class="item-controls">
							<span class="item-type">'.$menu['type'].'</span>
							<span class="item-edit ui-icon ui-icon-triangle-1-n"></span>
						</span>
					</div>
				</div>
				<div class="menu-item-settings" style="display: none;">
					<p class="link-p">
						<label class="link-edit" for="custom-menu-item-url">
							<span>URL</span>
							<input name="menu-item['.$menu['id'].'][menu-item-url]" type="text" class="code menu-item-textbox custom-menu-item-url" value="'.$menu['url'].'">
						</label>
					</p>
					<p class="link-p">
						<label class="link-edit" for="custom-menu-item-name">
							<span>链接文本</span>
							<input name="menu-item['.$menu['id'].'][menu-item-title]" type="text" class="regular-text menu-item-textbox input-with-default-title custom-menu-item-url" value="'.$menu['title'].'">
						</label>
					</p>
					<p class="link-p">
						<label class="link-edit" for="custom-menu-item-name">
							<span>新窗口打开链接</span>
							<input type="text" name="menu-item['.$menu['id'].'][menu-item-newtable]" class="checkbox" value="'.$menu['newtable'].'" style="display: none;">
						</label>
					</p>
					<p class="button-controls">
						<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text"  onclick="del_link('.$menu['id'].',\''.$menuID.'\');return false;">删除链接</span></button>
						<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text" onclick="save_link('.$menu['id'].');return false;">保存链接</span></button>
					</p>
				</div>
				<span id="'.$menu['id'].'"></span>
			</li>
';
            $html .= $html_tmp;
        } else {
            $html_tmp = '
			<li sid="menuItem_'.$menu['id'].'">
				<div class="menu-item-bar">
					<div class="menu-item-handle ui-sortable-handle">
						<span class="item-title"><span class="menu-item-title">'.$menu['title'].'</span> </span>
						<span class="item-controls">
							<span class="item-type">'.$menu['type'].'</span>
							<span class="item-edit ui-icon ui-icon-triangle-1-n"></span>
						</span>
					</div>
				</div>
				<div class="menu-item-settings" style="display: none;">
					<p class="link-p">
						<label class="link-edit" for="custom-menu-item-url">
							<span>URL</span>
							<input name="menu-item['.$menu['id'].'][menu-item-url]" type="text" class="code menu-item-textbox custom-menu-item-url" value="'.$menu['url'].'">
						</label>
					</p>
					<p class="link-p">
						<label class="link-edit" for="custom-menu-item-name">
							<span>链接文本</span>
							<input name="menu-item['.$menu['id'].'][menu-item-title]" type="text" class="regular-text menu-item-textbox input-with-default-title custom-menu-item-url" value="'.$menu['title'].'">
						</label>
					</p>
					<p class="link-p">
						<label class="link-edit" for="custom-menu-item-name">
							<span>新窗口打开链接</span>
							<input type="text" name="menu-item['.$menu['id'].'][menu-item-newtable]" class="checkbox" value="'.$menu['newtable'].'" style="display: none;">
						</label>
					</p>
					<p class="button-controls">
						<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text"  onclick="del_link('.$menu['id'].',\''.$menuID.'\');return false;">删除链接</span></button>
						<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text" onclick="save_link('.$menu['id'].');return false;">保存链接</span></button>
					</p>
				</div>
				<span id="'.$menu['id'].'"></span>
			</li>';
            $html = str_replace('<span id="'.$value.'"></span>', '<span id="'.$value.'"></span><ol>'.$html_tmp.'</ol>', $html);
        }
    }
    echo $html;
    ?>

							</ol>
						</div>
					</div>

					<div id="menu-edit-footer">
						<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="del_menu('<?php echo $menuID;
    ?>');return false;">删除导航</button>
					</div>
				</div>
			</div>
		</div>
<?php

}
?>

<div id="dialog" title="创建新导航" style="display:none;">
	<form id="edit" name="edit" method="post" action="">
	  <input id="edtID" name="creat" type="hidden" value="new">
	  <p>
		<span class="title">htmlID(英文标识):</span><span class="star">(*)</span><br>
		<input id="id" class="edit" size="40" name="id" maxlength="20" type="text" value="">
	  </p>

	  <p>
		<span class="title">名称:</span><span class="star">(*)</span><br>
		<input id="name" class="edit" size="40" name="name" maxlength="20" type="text" value="">
	  </p>
	  <p>
		<input type="submit" class="button" value="提交" id="btnPost">
	  </p>
	</form>
</div>
	</div>
  </div>
</div>
<?php
require $blogpath.'zb_system/admin/admin_footer.php';
RunTime();
?>