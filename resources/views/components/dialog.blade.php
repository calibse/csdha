<input id="g-dialog__toggler"type="checkbox">
<dialog open>
	<div class="g-flex__dialog">
		<div class="g-flex__dialog--header">
			<nav>
				<label class="g-dialog__collapser" for="g-dialog__toggler">{{ $closeText }}</label>
			</nav>
			<div class="g-flex__dialog--title">
				<p><strong>{{ $title }}</strong></p>
			</div>
		</div>
	{{ $slot }}
	</div>
</dialog>
<div class="g-dialog__overlay"></div>
