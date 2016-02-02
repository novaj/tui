<?php
	$this->assign("title", "Home - TUI Spain, las mejores ofertas de viaje");
	$this->Html->script(["home"], ["block" => "script"])
?>
<div class="modal fade" id="modalFlightsDetails">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?= __("Flight details") ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div id="outboundDetails" class="col-sm-6">
						<h4><?= __("Outbound flight") ?></h4>
						<p id="outboundCarrier"></p>
						<p id="outboundDate"></p>
						<p id="outboundAirports"></p>
						<p id="outboundTime"></p>
						<p id="outboundPrice" class="text-right price bg-info text-info"></p>
					</div>
						
					<div id="returnDetails" class="col-sm-6">
						<h4><?= __("Return flight") ?></h4>
						<p id="returnCarrier"></p>
						<p id="returnDate"></p>
						<p id="returnAirports"></p>
						<p id="returnTime"></p>
						<p id="returnPrice" class="text-right price bg-info text-info"></p>
					</div>
				</div>
			</div>
			<div class="modal-footer text-right">
				<button class="btn btn-warning" type="button" data-dismiss="modal"><?= __("Close") ?></button>
			</div>
		</div>
	</div>
</div>
<div class="text-center">
	<?= $this->Html->image("tui.png", ["class" => "logo img-responsive"]) ?>
</div>

<?= $this->Form->create(null, [
	"inputDefaults" => [
		"autocomplete" => "off",
		"div" => "form-group",
		"class" => "form-control selectpicker"
	]
]); ?>
<div class="flight-searcher">
	<div class="row">
		<div class="col-sm-6">
			<div class="searcher bg-info">
				<h3><?= __("Select flight") ?></h3>
				<div class="row">
					<div class="col-sm-4">
						<label>
							<input type="radio" name="flightType" value="round-trip" onchange="changeType(true);" autocomplete="off"> <?= __("Round-trip flight") ?>
						</label>
					</div>
					<div class="col-sm-3">
						<label>
							<input type="radio" name="flightType" value="one-way" checked onchange="changeType(false);" autocomplete="off"> <?= __("One way") ?>
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<?= $this->Form->input("departure", [
							"data-live-search" => "true",
							"data-size" => 10,
							"onchange" => "fillArrivals();",
							"label" => __("From"),
							"empty" => __("Select departure airport")
						]); ?>
					</div>
					<div class="col-sm-6">
						<?= $this->Form->input("arrival", [
							"data-live-search" => "true",
							"data-size" => 10,
							"empty" => __("Select arrival airport"),
							"options" => [],
							"label" => __("To"),
							"onchange" => "fillSchedule();"
						]); ?>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6">
						<?= $this->Form->input("scheduleDeparture", [
							"empty" => __("Select departure date"),
							"label" => __("Departure"),
							"class" => "form-control datetimepicker",
							"div" => "form-group has-feedback",
							"between" => "<div class='input-group'>",
							"after" => "<span class='input-group-addon'><i class='glyphicon glyphicon-calendar'></i></div>"
						]); ?>
					</div>
					<div class="col-sm-6">
						<?= $this->Form->input("scheduleReturn", [
							"empty" => __("Select return date"),
							"label" => __("Return"),
							"class" => "form-control datetimepicker",
							"div" => "form-group has-feedback",
							"between" => "<div class='input-group'>",
							"after" => "<span class='input-group-addon'><i class='glyphicon glyphicon-calendar'></i></div>",
							"disabled"
						]); ?>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-4">
						<?= $this->Form->input("adults", [
							"empty" => __("Select adults")
						]); ?>
					</div>
					<div class="col-sm-4">
						<?= $this->Form->input("children", [
							"options" => $children
						]); ?>
					</div>
					<div class="col-sm-4">
						<?= $this->Form->input("babies"); ?>
					</div>
				</div>

				<button id="sendButton" type="button" class="btn btn-block btn-info" onclick="searchAvailability();"><?= __("Search") ?></button>
			</div>
		</div>
		
		<div class="col-sm-6" id="availability"></div>
	</div>
</div>
<?= $this->Form->end(); ?>