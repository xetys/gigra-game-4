<!-- Slider anfang --> 
	<div class="coda-slider-wrapper">
		<div class="coda-slider preload" id="coda-slider-1">
			<div class="panel">
				<div class="panel-wrapper">
					<h2 class="title">{:l('v3_news')}</h2>
					<p>{:showNews()}</p>
				</div>
			</div>
			<div class="panel">
				<div class="panel-wrapper">
					<h2 class="title">{:l('v3_chat')}</h2>
					<p>{:showChat2()}</p>
				</div>
			</div>
			<div class="panel">
				<div class="panel-wrapper">
					<h2 class="title">{:l('v3_planets')}</h2>
					{:showPlaneten()}
				</div>
			</div>
            <div class="panel">
                <div class="panel-wrapper">
                    <h2 class="title" id="fleet-link">{:l('nav_fleet')}</h2>
                    
                    {:showFlotten()}
                </div>
            </div>
            <div class="panel">
                <div class="panel-wrapper">
                    <h2 class="title" id="fleet-link">{:l('bonus_packs')}</h2>
                   {:showBonusPacks()}
                </div>
            </div>
            
            
            
            
            
		</div><!-- slider -->
	</div><!-- slider-wrapper -->
<!-- Slider Ende -->