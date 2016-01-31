<?php if(empty($availability["flights"])) : ?>
<div class="bg-danger text-danger text-center">
	<h4><?= __("The system is not available at the moment") ?></h4>
	<p><?= __("Please, try again later.") ?></p>
</div>
<?php else : ?>
<h3><?= __("Outbound") ?></h3>
<?php if(!empty($availability["flights"]["OUT"])) : ?>
<table class="table table-striped">
	<tbody>
<?php foreach($availability["flights"]["OUT"] as $idx => $flight) : ?>
		<tr id="outboundFlight<?= $idx ?>">
			<td>
				<input type="radio" name="outboundFlight" onchange="enableConfirm(<?= $idx ?>, 'outbound');">
				<span id="outboundCarrier" class="hidden"><?= $flight["carrier"].$flight["number"] ?></span>
			</td>
			<td id="outboundDate">
				<?php
					$departDate = new DateTime($flight["depart"]["datetime"]);
					$arrivalDate = new DateTime($flight["arrival"]["datetime"]);
					echo $departDate->format("D d/m/Y");
				?>
			</td>
			<td id="outboundAirports"><?= $flight["depart"]["airport"]["name"]." => ".$flight["arrival"]["airport"]["name"] ?></td>
			<td id="outboundTime"><?= $departDate->format("H:i")." => ".$arrivalDate->format("H:i") ?></td>
			<td id="outboundPrice">&euro; <?= number_format($flight["price"], 2) ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>

<?php if($flightType == "round-trip") : ?>
<h3><?= __("Return") ?></h3>
<?php if(!empty($availability["flights"]["RET"])) : ?>
<table class="table table-striped">
	<tbody>
<?php foreach($availability["flights"]["RET"] as $idx => $flight) : ?>
		<tr id="returnFlight<?= $idx ?>">
			<td>
				<input type="radio" name="returnFlight" onchange="enableConfirm(<?= $idx ?>, 'return');">
				<span id="returnCarrier" class="hidden"><?= $flight["carrier"].$flight["number"] ?></span>
			</td>
			<td id="returnDate">
				<?php
					$departDate = new DateTime($flight["depart"]["datetime"]);
					$arrivalDate = new DateTime($flight["arrival"]["datetime"]);
					echo $departDate->format("D d/m/Y");
				?>
			</td>
			<td id="returnAirports"><?= $flight["depart"]["airport"]["name"]." => ".$flight["arrival"]["airport"]["name"] ?></td>
			<td id="returnTime"><?= $departDate->format("H:i")." => ".$arrivalDate->format("H:i") ?></td>
			<td id="returnPrice">&euro; <?= number_format($flight["price"], 2) ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
<button id="confirmButton" class="btn btn-success btn-block disabled" disabled type="button" data-toggle="modal" data-target="#modalFlightsDetails"><?= __("Confirm") ?></button>
<?php else : ?>
<h4><?= __("No matches found") ?></h4>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>