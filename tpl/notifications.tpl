{function button($onclick,$text,$class = 'einst_btn_save',$span = true)}
    {if $span}
        {$tag = "span"}
    {else}
        {$tag = "div"}
    {/if}
    <a href='javascript:void(0)' onclick="{$onclick}"><{$tag} class="class_btn {$class}">{$text}</{$tag}></a>
{/function}
<div class="hidden error" id="error-noships">
    {:l('fleet_error_no_ship')}
</div>

<div class="hidden error" id="error-invalidships">
    {:l('fleet_error_invalid_ships')}
</div>

<div class="hidden error" id="error-no-missions">
    {:l('fleet_error_no_missions')}
</div>

<div class="hidden error" id="error-no-planet">
    {:l('fleet_error_no_planet')}
</div>

<div class="hidden error" id="error-invalid-mission">
    {:l('fleet_error_invalid_mission')}
</div>

<div class="hidden error" id="error-invalid-mission">
    {:l('fleet_error_invalid_mission')}
</div>

<div class="hidden error" id="error-invalid-res">
    {:l('fleet_error_invalid_res')}
</div>

<div class="hidden error" id="error-not-enough-res">
    {:l('fleet_error_not_enough_res')}
</div>

<div class="hidden error" id="error-not-enough-capa">
    {:l('fleet_error_not_enough_capa')}
</div>

<div class="hidden error" id="error-not-enough-fuel">
    {:l('fleet_error_not_enough_fuel')}
</div>

<div class="hidden error" id="error-aks-not-in-time">
    {:l('fleet_error_aks_not_in_time')}
</div>
<div class="hidden error" id="error-me-in-umod">
    {:l('fleet_error_me_in_umod')}
</div>

<div class="hidden error" id="error-target-in-umod">
    {:l('fleet_error_target_in_umod')}
</div>

<div class="hidden success" id="fleet-success">
    {:l('fleet_success')}
</div>
<div class="hidden error" id="general-error">
    
</div>
<div class="hidden success" id="general-success">
    
</div>
<div class="hidden success" id="save-success">
    {:l('eins_success')}
</div>
<div class="hidden" id="tutBorder">
</div>
<div class="hidden" id="tutText">
    <div>
    Hier könnte demnächst ein Tutorialtext stehen. Dann wird dir sicher klar, was das hier ist, wie du das benutzen kannst usw :)
    </div>
    <br>
    <br>
    {button('closeTutObj()',l('close'))}
    {button('nextTutorial()',l('fleet_next'))}
</div>
