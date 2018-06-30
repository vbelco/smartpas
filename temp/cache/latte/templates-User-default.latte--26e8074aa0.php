<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/User/default.latte

use Latte\Runtime as LR;

class Template26e8074aa0 extends Latte\Runtime\Template
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
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.user_management")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($user->loggedIn) {
?><div>
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("User:default")) ?>"> -Vase udaje- </a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("User:organizacia")) ?>"> -Organizacia- </a></li>
    </ul>
</div>    

<div class="panel panel-default"> 
    <h2>-Vase udaje-</h2>
    <div class="panel-body">
<?php
			/* line 23 */ $_tmp = $this->global->uiControl->getComponent("userFormEdit");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
?>
    </div>
</div>    

<div class="panel panel-default">    
    <h2>-Zmena hesla-</h2>
    <div class="panel-body">
<?php
			/* line 30 */ $_tmp = $this->global->uiControl->getComponent("userFormPasswordEdit");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
?>
    </div>
</div>     
    
<?php
		}
		
	}

}
