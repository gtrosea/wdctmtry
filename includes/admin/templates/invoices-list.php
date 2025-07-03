<div class="wrap">

	<form method="get" action="">

		<input type="hidden" name="page" value="weddingsaas-invoice" />

		<?php $list_table->views(); ?>

		<?php $list_table->search_box( __( 'Search Invoice', 'wds-notrans' ), 'wds-button' ); ?>

		<?php $list_table->display(); ?>

	</form>

</div>