<script setup lang="ts">

import dayjs from "dayjs";
import {formatDateRange} from "~/utils/date";

const q = ref("")
const place = ref("")
const dur = ref("")
const showFilter = ref(false)
const loading = ref(false)
const showItems = ref(false)
const NEOGRAN = "Неограниченно"
const count = ref(NEOGRAN)
const date = ref(new Date())
const travelType = ref<{name: string; code: number}>();
const travelTypes = ref([
  { name: 'Пеший поход', code: 1 },
  { name: 'Автопотишествие', code: 2 },
  { name: 'Вплавь', code: 3 },
  { name: 'Самолет', code: 4 },
  { name: 'Поезд', code: 5 },
  { name: 'Экстрим', code: 5 },
  { name: 'Спокойный', code: 5 },
  { name: 'Лес', code: 5 },
  { name: 'Горы', code: 5 },
  { name: 'Море', code: 5 }
]);

const {recognize} = useDatarec()

const api = useApi()

const items = ref([])
const onSubmit = async () => {


  let endDate: Date|null = recognize(`через ${dur.value}`, new Date(date.value))
  if (dur.value == "") {
    endDate = null
  }

  const start = date.value ? dayjs(date.value).format("YYYY-MM-DD") : "null"
  const end = endDate ? dayjs(endDate).format("YYYY-MM-DD") : "null"

  showItems.value = true
  showFilter.value = false
  loading.value = true
  const {data} = await api.events.index({
    q: q.value + " " + place.value,
    tags: [travelType.value?.code],
    date:  start+"," + end,
    maxUsers: count.value == NEOGRAN ? null : count.value,
    limit: 3,
  })
  items.value = data

  loading.value = false

}
const toggleFilter = () => {
  showFilter.value = !showFilter.value
  showItems.value = false
}

watch(count, (newVal) => {
  if (newVal == 0) {
    count.value = NEOGRAN
  }
});

</script>

<template>
  <div>
    <div class="main__wrapper">
      <h2>Куда отправимся?</h2>
      <form @submit.prevent="onSubmit" class="main__search">
        <div class="main__settings" v-show="showFilter">
          <div class="settings__date"><h4>дата</h4><Calendar v-model="date" dateFormat="dd.mm.yy" /></div>
          <div class="settings__type"><h4>вид отдыха</h4><Dropdown v-model="travelType" :options="travelTypes" optionLabel="name" placeholder="Пеший поход" /></div>
          <div class="settings__place"><h4>место</h4><input v-model="place" type="text" placeholder="Алтай"/></div>
          <div class="settings__duration"><h4>длительность</h4><input v-model="dur" type="text" placeholder="Неделя"/></div>
          <div class="settings__amount"><h4>количество человек</h4>
            <InputText v-model.number="count" />
            <Slider v-model="count" /></div>
        </div>
        <div class="search__string"><input v-model="q" type="text" name="q" placeholder="Горы Алтая" /></div>
        <button type="button" class="search__options" @click="toggleFilter"><NuxtImg src="/img/Options-icon.svg" /></button>
        <button class="search__button" type="submit"><NuxtImg src="/img/Search-icon.svg" /></button>

        <div v-show="showItems" class="main__list_wrapper">
          <div v-if="!loading" v-for="item in items" class="main__list_wrapper-item">
            <NuxtLink :href="'/trips/' + item.id" class="main__list_wrapper-item__img"><img src="https://media.discordapp.net/attachments/1233154102725447731/1233819318001860618/golden-sunset.jpg?ex=662e7b48&is=662d29c8&hm=c89dcf6285ff91721909812e0ec21f06687f538e8ed56627af1afa08c2e35e1f&=&format=webp&width=350&height=350"/></NuxtLink>
            <div class="main__list_wrapper-item__container">
              <div class="main__list_wrapper-item__content">
                <NuxtLink :href="'/trips/' + item.id"><h3>{{item.name}}</h3></NuxtLink>
                <div class="tags">
                  <div v-for="tag in item.tags" class="tag-item">{{ tag.tag }}</div>
                </div>
                <p>{{item.description}}</p>
              </div>
              <div class="main__list_wrapper-item__info">
                <div v-if="item.maxUsers != null" class="item__users-count"><NuxtImg src="/img/user.jpg"/>{{item.maxUsers}}</div>
                <div class="item__date"><NuxtImg src="/img/calendar.jpg"/>
                  {{ formatDateRange(item.dates.start, item.dates.end) }}</div>
                <NuxtLink class="item__user" :href="'/users/'+item.owner.id"><NuxtImg src="/img/Profile-icon.svg"/>{{item.owner.name}}</NuxtLink>
              </div>
            </div>

          </div>

          <div v-if="!loading && items.length == 0" class="main__list_wrapper-item">
            <h3>Ничего не найдено</h3>
          </div>
          <div v-if="loading" class="main__list_wrapper-item">Загрузка...</div>
          <NuxtLink v-if="items.length != 0" href="/trips">Смотреть ещё</NuxtLink>
        </div>
      </form>
      <div class="main__illustration__wrapper">
        <div class="main__illustration__item">
          <figure>
            <NuxtImg src="/img/illust-1.svg"/>
            <figcaption>
              <h3>Стройте маршруты</h3>
              <p class="p__1">Приложение  само подберёт<br>путешествия, основываясь<br>на ваших предпочтениях</p>
            </figcaption>
          </figure>
        </div>
        <div class="main__illustration__item">
          <figure>
            <NuxtImg src="/img/illust-2.svg"/>
            <figcaption>
              <h3>Ищите попутчиков</h3>
              <p>Расскажите о своих планах<br>и найдите единомышленников,<br>готовых составить вам компанию</p>
            </figcaption>
          </figure>
        </div>
        <div class="main__illustration__item">
          <figure>
            <NuxtImg src="/img/illust-3.svg"/>
            <figcaption>
              <h3>Делитесь эмоциями</h3>
              <p>Рассказывайте о впечатлениях<br>от путешествий в собственном<br>блоге</p>
            </figcaption>
          </figure>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>