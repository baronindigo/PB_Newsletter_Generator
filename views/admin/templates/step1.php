<section class="step1">
	<h2>Step 1: Configure Message</h2>
	<p>You need to fill the form with the Message setup</p>
	<div class="message-form">
		<div class="form">
			<p>
				<span class="form-label">From Name</span>
				<input type="text" class="js-fromname form-text" placeholder="Enter name of sender" />
			</p>

			<p>
				<span class="form-label">From Email</span>
				<input type="text" class="js-fromemail form-text" placeholder="Enter e-mail of sender" />
			</p>

			<p>
				<span class="form-label">Reply To</span>
				<input type="text" class="js-reply2 form-text" placeholder="Enter reply email for the recipient to reply to" />
			</p>

			<p>
				<span class="form-label">Subject</span>
				<input type="text" class="js-subject form-text" placeholder="Enter the Subject text" />
			</p>

			<p>
				<span class="form-label">Campaign Name:</span>
				<input type="text" name="campaign_name" class="js-campaign-name form-text" value="" placeholder="What is the name of the Campaign?" />
			</p>

			<p>
				<span class="form-label">Send Date:</span>
				<input type="text" name="featured_date" id="featured_date" class="js-email-date form-text" value="" placeholder="When?" />
			</p>

			<p>
				<span class="form-label">Send Time:</span>
				<select class="js-email-hour">
					<?php for ($i=1; $i<=24; $i++) : ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?>:00</option>
					<?php endfor; ?>
				</select>
			</p>

			<p>
				<span class="form-label">Template Type:</span>
				<select class="js-template-name">
					<?php $templates = new PB_Newsletter_Gen_Templates(); ?>
					<?php foreach ($templates->getTemplates() as $template => $value) : ?>
						<option value="<?php echo $template; ?>"><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			</p>

			<p>
				<span class="form-label">Subscribe List:</span>
				<select class="js-lists-select"></select>
			</p>
		</div>

		<div class="js-messages"></div>
	</div>
</section>