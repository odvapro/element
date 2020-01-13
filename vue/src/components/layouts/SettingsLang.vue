<template>
	<div class="settings-lang-wr">
		<div class="settings-lang">
			<div class="settings-lang__label">
				{{ $t('settingsLang.select_language') }}
			</div>
			<div class="settings-lang-list">
				<List>
					<template v-slot:selected>
						<ListOption>{{ currentLang }}</ListOption>
					</template>
					<ListOption
						v-for="(lang, langIndex) in languages"
						:key="langIndex"
						@select="changeLang(lang)"
					>{{ lang.long }}</ListOption>
				</List>
			</div>
		</div>
	</div>
</template>
<script>
	import List from '@/components/forms/List.vue';
	import ListOption from '@/components/forms/ListOption.vue';

	export default
	{
		components: { List, ListOption },
		computed:
		{
			currentLang()
			{
				return this.$store.state.languages.currentLang.long;
			},
			languages()
			{
				return this.$store.state.languages.list;
			}
		},
		methods:
		{
			changeLang(newLang)
			{
				this.$store.dispatch('setLanguage', {newLang, id: this.$store.state.users.authUser.id});
				this.$store.commit('setLanguage', newLang);
			}
		}
	}
</script>
<style lang="scss">
	.settings-lang__label
	{
		margin-bottom: 10px;
	}
	.settings-lang-list
	{
		cursor: pointer;
		max-width: 160px;
		border: 1px solid rgba(103, 115, 135, 0.4);
		padding-left: 4px;
		padding-right: 10px;
		position: relative;
		display: flex;
		align-items: center;
		height: 30px;
		border-radius: 2px;
	}
</style>