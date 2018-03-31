<div id="V3_Content">
    <div class="class_content_wrapper">
        <!-- {:l('galaxy_galaxy')} -->
        <div class="info_first_head"> 
            <form action="ajax.php" name="galForm" onsubmit="showGalaxy(this.g.value,this.s.value);return false;">
            <input type="hidden" name="type" value="galaxy">
                <input type="button" value="<" onclick="showGalaxy(Number(g.value)-1,s.value);">
                <input type="text" id="galaxy-g" name="g" value="{$g}" class="div_konstruktion_input" tabindex="1">
                <input type="button" value=">" onclick="showGalaxy(Number(g.value)+1,s.value);">
                <input type="button" value="<" onclick="showGalaxy(g.value,Number(s.value)-1);">
                <input type="text" id="galaxy-s" name="s" value="{$s}" class="div_konstruktion_input" tabindex="2">
                <input type="button" value=">" onclick="showGalaxy(g.value,Number(s.value)+1);">
                <input type="submit" value="{:l('galaxy_show')}" class="div_konstruktion_input" tabindex="3">
            </form>
        </div>
        
        <div id="galaxy-container">
            {$lsCoords = $g.':'.$s}
            {:showGalaxy($lsCoords)}
        </div>
    </div>

</div>