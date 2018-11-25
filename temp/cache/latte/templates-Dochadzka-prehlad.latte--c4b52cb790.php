<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Dochadzka/prehlad.latte

use Latte\Runtime as LR;

class Templatec4b52cb790 extends Latte\Runtime\Template
{
	public $blocks = [
		'banner' => 'blockBanner',
		'title' => 'blockTitle',
		'content' => 'blockContent',
		'_vypisArea' => 'blockVypisArea',
	];

	public $blockTypes = [
		'banner' => 'html',
		'title' => 'html',
		'content' => 'html',
		'_vypisArea' => 'html',
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
		if (isset($this->params['index'])) trigger_error('Variable $index overwritten in foreach on line 76');
		if (isset($this->params['post2'])) trigger_error('Variable $post2 overwritten in foreach on line 76');
		if (isset($this->params['osoba'])) trigger_error('Variable $osoba overwritten in foreach on line 59');
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
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:prehlad")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.prehlad")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:nastavenie")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.settings")) ?></a></li>
    </ul>
</div>
    
<div class="container">
    <div class="panel panel-default"> 
    <div class="panel-body">
    <?php
			/* line 27 */
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["prehladForm"], []);
?>

        <div class="row required">
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["datum_od"]->getLabel()) echo $_label ?></div>
            <div class="col-sm-4 input-group date required" id="form-date-od" >
                        <?php echo end($this->global->formsStack)["datum_od"]->getControl() /* line 31 */ ?>

                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["datum_do"]->getLabel()) echo $_label ?></div>
            <div class="col-sm-4 input-group date required" id ="form-date-do">
                        <?php echo end($this->global->formsStack)["datum_do"]->getControl() /* line 37 */ ?>

                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div> 
                        
           
        <div class="row">
            <div class="col-sm-1"> <?php if ($_label = end($this->global->formsStack)["osoby"]->getLabel()) echo $_label ?></div>
            <div class="col-sm-4"> <?php echo end($this->global->formsStack)["osoby"]->getControl() /* line 46 */ ?></div>
        </div>
        <div class="my_last_row">
            <div class="col-sm-1"> </div>
            <div class="col-sm-4"><?php echo end($this->global->formsStack)["send"]->getControl() /* line 50 */ ?></div>
        </div>
    <?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

    </div>
    </div>
</div>   

<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('vypisArea')) ?>"><?php $this->renderBlock('_vypisArea', $this->params) ?></div>

<script type="text/javascript">
    $('#form-date-od').datetimepicker({
        minView: 2,
        autoclose: 1,
        todayBtn:  1,
        useCurrent: true,
        format: "dd.mm.yyyy",
        language:  <?php echo LR\Filters::escapeJs($activeLocale) /* line 129 */ ?>

    });
    $('#form-date-do').datetimepicker({
        minView: 2,
        autoclose: 1,
        todayBtn:  1,
        format: "dd.mm.yyyy",
        language:  <?php echo LR\Filters::escapeJs($activeLocale) /* line 136 */ ?>

    });
</script> 


<?php
		}
?> 
<?php
	}


	function blockVypisArea($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("vypisArea", "static");
		if (isset($meno_osoby)) {
			$iterations = 0;
			foreach ($osoby as $osoba) {
?>
    <div class="table-responsive">
        <h3><?php echo LR\Filters::escapeHtmlText($meno_osoby[$osoba]) /* line 61 */ ?> ( <?php echo LR\Filters::escapeHtmlText($osoba) /* line 61 */ ?> )</h3>
        
<?php
				if (isset($posts_zaokruhlena_dochadzka[$osoba])) {
?>
            
        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.celkovy_cas")) ?>: 
        <div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId("celkovyCas-$osoba")) ?>"><?php
					$this->global->snippetDriver->enter("celkovyCas-$osoba", "dynamic");
?>

        <?php echo LR\Filters::escapeHtmlText($celkovy_cas_dochadzky[$osoba]["hodiny"]) /* line 67 */ ?> : <?php
					echo LR\Filters::escapeHtmlText($celkovy_cas_dochadzky[$osoba]["minuty"]) /* line 67 */ ?>

<?php
					$this->global->snippetDriver->leave();
					?></div>        <table class=" table table-hover">
            <tr>
                <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.prichod")) ?></th>
                <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.odchod")) ?></th>
                <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.time_at_work")) ?></th>
            </tr>
        
<?php
					$iterations = 0;
					foreach ($posts_zaokruhlena_dochadzka[$osoba] as $index => $post2) {
?>
            <tr>
                <td>
<?php
						if ($post2['prichod']) {
							?>                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $post2['prichod'], "d. m. Y G:i:s")) /* line 80 */ ?>

<?php
							if ($vypisovat_realne_casy) {
?>
                            <br>
                            ( <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $posts_raw_dochadzka[$osoba][$index]['prichod'], "G:i:s")) /* line 83 */ ?> )
<?php
							}
						}
						else {
							?>                        <div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId("prichod-$index")) ?>"><?php
							$this->global->snippetDriver->enter("prichod-$index", "dynamic");
?>

                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.nedefinovane")) ?>

                        <a class=ajax href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Edit!", [$index])) ?>"><button type="button" class="btn btn-default"><?php
							echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.edit")) ?></button></a>
<?php
							$this->global->snippetDriver->leave();
							?></div><?php
						}
?>
                </td>
                <td>
<?php
						if ($post2['odchod']) {
							?>                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $post2['odchod'], "d. m. Y G:i:s")) /* line 94 */ ?>

<?php
							if ($vypisovat_realne_casy) {
?>
                            <br>
                            ( <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $posts_raw_dochadzka[$osoba][$index]['odchod'], "G:i:s")) /* line 97 */ ?> )
<?php
							}
						}
						else {
							?>                        <div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId("odchod-$index")) ?>"><?php
							$this->global->snippetDriver->enter("odchod-$index", "dynamic");
?>

                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.nedefinovane")) ?>

                        <a class=ajax href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Edit!", [$index])) ?>"><button type="button" class="btn btn-default"><?php
							echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.edit")) ?></button></a>
<?php
							$this->global->snippetDriver->leave();
							?></div><?php
						}
?>
                </td>
                <td>
                    <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $post2['cas_v_praci'], "%h:%I")) /* line 107 */ ?>

                </td>
            </tr>
<?php
						$iterations++;
					}
?>
        </table>
<?php
				}
				else {
					?>        <div><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.none_definition_attendance")) ?></div>
<?php
				}
?>
        
    </div>
<?php
				$iterations++;
			}
		}
		$this->global->snippetDriver->leave();
		
	}

}
