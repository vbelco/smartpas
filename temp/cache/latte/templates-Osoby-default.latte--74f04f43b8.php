<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Osoby/default.latte

use Latte\Runtime as LR;

class Template74f04f43b8 extends Latte\Runtime\Template
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
		if (isset($this->params['post'])) trigger_error('Variable $post overwritten in foreach on line 23');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockBanner($_args)
	{
		
	}


	function blockTitle($_args)
	{
		extract($_args);
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.osoby_management")) ?></h1>
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

<div class="table-responsive">
<table class=" table table-hover">
<?php
			$iterations = 0;
			foreach ($posts as $post) {
?>
        <tr>
            <td><?php echo LR\Filters::escapeHtmlText($post->id) /* line 25 */ ?> </td>
            <td><?php echo LR\Filters::escapeHtmlText($post->meno) /* line 26 */ ?></td>
            <td>
                <a class="btn btn-default" role="button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("edituj", [$post->id])) ?>"><?php
				echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.edit")) ?></a> 
            </td> 
        </tr>
<?php
				$iterations++;
			}
?>
</table>
</div>
<?php
		}
		
	}

}
