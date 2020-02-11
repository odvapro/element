<template>
	<div>
		<span @click="openPopup">sfdsfds</span>
		<div >
				<!-- v-click-outside="closePopup" -->
			<DetailPopup
				v-if="view === 'detail'"
				:name="'tableDetail'"
				:id="1"
				:tableCode="'block_type'"
				:isPopupVisible="addPopupVisible"
				@close="closePopup"
			/>
		</div>
	</div>
</template>
<script>
	import DetailPopup from '@/components/popups/DetailPopup.vue'

	export default
	{
		props:['view'],
		components: {DetailPopup},
		data()
		{
			return {
				addPopupVisible: false,
			}
		},
		methods:
		{
			openPopup()
			{
				this.addPopupVisible = true;
			},
			closePopup()
			{
				this.addPopupVisible = false;
			},
		}
	}
</script>