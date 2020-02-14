<template>
	<div class="detail-popup">
		<Popup
			:visible.sync="isPopupVisible"
		>
			<Detail
				:tableCode="tableCode"
				:name="name"
				:id="id"
				@cancel="cancel"
				@openDetail="openDetail"
				@saveElement="saveElement"
				@removeElement="removeElement"
				@createElement="createElement"
			/>
		</Popup>
	</div>
</template>
<script>
	import Detail from '@/components/tviews/Detail.vue'
	export default
	{
		props:
		[
			'tableCode',
			'name',
			'id',
			'visible'
		],
		components: {Detail},
		computed:
		{
			isPopupVisible:
			{
				get()
				{
					return this.visible
				},
				set(val)
				{
					this.$emit('update:visible', val);
				}
			}
		},
		methods:
		{
			cancel()
			{
				this.isPopupVisible = false;
			},
			openDetail(data)
			{
				this.$emit('openDetail', data);
			},
			saveElement(data)
			{
				this.$emit('saveElement', data);
			},
			removeElement(data)
			{
				this.$emit('removeElement', data);
			},
			createElement(data)
			{
				this.$emit('createElement', data);
			},
		}
	}
</script>
<style lang="scss">
	.detail-popup
	{
		position: absolute;
		z-index: 1;
		.popup-block
		{
			min-width: 1000px;
		}
	}
</style>