<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/RFID/edituj.latte

use Latte\Runtime as LR;

class Templatecd5e4181a7 extends Latte\Runtime\Template
{
	public $blocks = [
		'banner' => 'blockBanner',
		'title' => 'blockTitle',
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'banner' => 'html',
		'title' => 'html',
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('banner', get_defined_vars());
?>

<?php
		$this->renderBlock('title', get_defined_vars());
?>


<?php
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockBanner($_args)
	{
		
	}


	function blockTitle($_args)
	{
		extract($_args);
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.editovat_rfid")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($user->loggedIn) {
?> <div>
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("RFID:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_rfid.prehlad")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("RFID:pridaj")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_rfid.add")) ?></a></li>
    </ul>
</div>
 
<div>
<?php
			/* line 20 */ $_tmp = $this->global->uiControl->getComponent("rFIDForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
?>
</div>

<div>
<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("RFID:deaktivuj", [$rfid_id])) ?>"><button type="button" class="btn btn-danger"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.deactivate_rfid")) ?></button></a>
</div>    

<?php
		}
		
	}

}
