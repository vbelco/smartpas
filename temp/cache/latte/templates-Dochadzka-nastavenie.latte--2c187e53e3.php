<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Dochadzka/nastavenie.latte

use Latte\Runtime as LR;

class Template2c187e53e3 extends Latte\Runtime\Template
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
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.dochadzka")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
?>

   

<?php
		if ($user->loggedIn) {
?>    
<div>
    <ul class="nav nav-pills">
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.current")) ?></a></li>
        <li ><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:prehlad")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.prehlad")) ?></a></li>
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:nastavenie")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.settings")) ?></a></li>
    </ul>
</div>
    
<?php
			/* line 24 */ $_tmp = $this->global->uiControl->getComponent("nastavenieForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
?>

<?php
		}
?> 
<?php
	}

}
