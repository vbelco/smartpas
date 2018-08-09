<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Osoby/kontrola.latte

use Latte\Runtime as LR;

class Templateb520a77c36 extends Latte\Runtime\Template
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
?>    <h1>-Moznost osob vykonavat kontrolu svojej vlastnej dochadzky-</h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($user->loggedIn) {
?>    
<div>
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Osoby:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_osoby.prehlad")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Osoby:pridaj")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_osoby.pridaj")) ?></a></li>
    </ul>
</div>
    <h2><?php echo LR\Filters::escapeHtmlText($meno) /* line 19 */ ?></h2>
<?php
			if (!$definovane) {
				?>        <div>-<?php echo LR\Filters::escapeHtmlText($meno) /* line 21 */ ?> zatial nema moznost prezerat si svoju dochadzku. Vytvorte ju-</div>
<?php
			}
			else {
				?>        <div>-<?php echo LR\Filters::escapeHtmlText($meno) /* line 23 */ ?> uz ma moznost prezerat si svoju dochadzku. Mozete mu zmenit heslo-</div>
<?php
			}
?>
    
<?php
			/* line 26 */ $_tmp = $this->global->uiControl->getComponent("kontrolaForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
?>

<?php
		}
		
	}

}
