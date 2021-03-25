<template>
	<div class="update-wrapper">
		<div class="update-head">
			<div class="update-head__burger"><MobileBurger/></div>
			<div class="update-head-name">
				<div class="update-icon-wrapper"> ⚙️ </div>
				<div class="update-name-wrapper">
					<div class="update-head-label">{{$t('update')}}</div>
					<div class="update-head-descr">{{$t('pages.update.check_and_update_element')}}</div>
				</div>
			</div>
		</div>
		<div class="update-content">
			<div class="update-version">
				{{$t('pages.update.current_version')}} <em>{{ currentVersion }}</em>
				<div v-if="showLatestIsInstalled && !showLoader">{{$t('pages.update.you_have_the_latest_version')}}</div>
			</div>
			<template v-if="successUpdate">
				<div class="update-success">{{$t('pages.update.system_has_been_updated_successfully')}}</div>
			</template>
			<Loader class="update__loader" v-if="showLoader" />
			<div class="update-buttons" v-if="!successUpdate">
				<button @click="checkVersion()" class="el-gbtn">{{$t('pages.update.check_version')}}</button>
				<button @click="update()" v-if="canUpdate" class="el-btn">{{$t('pages.update.update_to')}} {{ newVersion }}</button>
			</div>
		</div>
	</div>
</template>
<script>
	import Loader from '@/components/forms/Loader.vue';
	import MobileBurger from '@/components/blocks/MobileBurger.vue';

	export default
	{
		components:{Loader,MobileBurger},
		data()
		{
			return {
				currentVersion        : '',
				canUpdate             : false,
				newVersion            : '',
				showLatestIsInstalled : false,
				successUpdate         : false,
				showLoader            : false
			}
		},
		methods:
		{
			/**
			 * Проверка существования обновления
			 */
			async checkVersion()
			{
				this.showLoader = true;
				let result = await this.$axios.get('/settings/checkVersion/');
				this.showLoader = false;
				if(typeof result.data.success == 'undefined')
					return this.ElMessage.error(this.$t('elMessages.something_goes_wrong'));
				if(result.data.result == true)
				{
					this.canUpdate = true;
					this.newVersion = result.data.new_version;
				}
				else
					this.showLatestIsInstalled = true;
			},

			/**
			 * Обновление системы
			 */
			async update()
			{
				this.showLoader = true;
				let result = await this.$axios.get('/settings/update/');
				this.showLoader = false;
				if(typeof result.data.success != 'undefined' && result.data.success)
					this.successUpdate = true;
			}
		},
		/**
		 * Доастаем текущую версию системы
		 */
		async mounted()
		{
			let result = await this.$axios.get('/settings/getCurrentVersion/');
			if(typeof result.data.success != 'undefined' && result.data.success)
				this.currentVersion = result.data.version;
		}
	}
</script>
<style lang="scss">
	.update-wrapper {padding: 23px 0 23px 21px; }
	.update-head
	{
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		margin-bottom: 13px;
		padding-right: 95px;
		justify-content: flex-start;
		padding-right: 20px;
		align-items: center;
	}
	.update-head-name
	{
		display: flex;
		align-items: center;
	}
	.update-icon-wrapper
	{
		margin-right: 3px;
		font-weight: 500;
		line-height: normal;
		font-size: 20px;
		color: #000000;
	}
	.update-head-label
	{
		font-family: $rBold;
		font-size: 20px;
		color: #191C21;
		line-height: 22px;
		text-transform: capitalize;
	}
	.update-head-descr
	{
		color: rgba(103, 115, 135, 0.4);
		font-size: 10px;
		text-transform: lowercase;
	}
	.update-version
	{
		font-style: normal;
		font-weight: normal;
		font-size: 14px;
		line-height: 18px;
		color: #677387;
		margin-bottom: 13px;
		em{font-style: normal; font-weight: bold; color:#191C21;}
	}
	.update-buttons .el-gbtn {margin-right: 17px; }
	.update-success {color:#3A8406; font-size: 14px;}
	.update__loader{margin-bottom: 20px;}
	.update-head__burger { margin-right: 20px; }
	@media (max-width: 768px)
	{
		.update-wrapper { min-width: 375px; }
	}
</style>
