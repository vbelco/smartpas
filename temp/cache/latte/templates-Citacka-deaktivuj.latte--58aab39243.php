<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Citacka/deaktivuj.latte

use Latte\Runtime as LR;

class Template58aab39243 extends Latte\Runtime\Template
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
		?>    <h1 class="text-danger"><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.reader_deactivate")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($user->loggedIn) {
?> <div>
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Citacka:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.citacka.prehlad")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Citacka:pridaj")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.citacka.pridaj")) ?></a></li>
    </ul>
</div>
 
<div>
<?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.reader_deactivation_confirmation")) ?> : <?php
			echo LR\Filters::escapeHtmlText($number) /* line 20 */ ?> ?
</div>

<div>
    <a  href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Citacka:deaktivuj", [$citacka_id , 'proceed' => 1])) ?>"><button type="button" class="btn btn-danger"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.deactivate")) ?></button></a>
</div>    
<?php
		}
		
	}

}
