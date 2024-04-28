<script setup lang="ts">
import {object, string} from "yup"
const {event} = defineProps(['event'])

const {handleSubmit, defineInputBinds, errors, setFieldValue } = useForm({
  validationSchema: object({
    name: string()
        .required(),

    description: string()
        .required(),

  }),
  initialValues: {
    name: event?.name || "",
    description:  event?.name || "",
  },
})
const nameField = defineInputBinds("name")
const descriptionField = defineInputBinds("description")
const state = ref({
  tags: event?.tags || [],
  places:  event?.places || []
})
const tags = ref([
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

const placeQ = ref("")
const places = ref([])

const api = useApi()
const onInput = useDebounceFn(async () => {
  if (placeQ.value == "") {
    return
  }

  const {data} = await api.places.search(placeQ.value)
  places.value = data
}, 300)
const onSubmit = handleSubmit(async (values) => {

  const data = {
    ...values,
    status: "STATUS_CREATED",
    places: state.value.places,
    tags: state.value.tags.map(i => i.code),
    // HARDCODE
    maxUsers: null,
    requestToJoin: false,
  }

  if (event == null) {
    await api.events.create(data)
    return
  }

})

const onSelect = (val) => {

  if (typeof val.value == "string") {
    return
  }

  // @ts-ignore
  if (state.value.places.findIndex(i => i.name == val.value.name && i.type == val.value.type) >= 0) {
    placeQ.value = ''
    return;
  }

  state.value.places.push({name: val.value.name_id, title: val.value.name, type: val.value.type})
  placeQ.value = ''
}

const deletePlace = (idx) => {
  state.value.places.splice(idx, 1)
}
</script>

<template>
  <form @submit.prevent="onSubmit" class="article__form">
    <h3>Создание путешествия</h3>
    <div class="article__form_main">
      <InputText
          :unstyled="true"
          class="article__form_main-title"
          v-bind="nameField"
          type="text"
          placeholder="Введите заголовок путешествия"/>
      <Textarea :unstyled="true"
                placeholder="Опишите свой маршрут"
                v-bind="descriptionField"
                autoResize rows="5" cols="30" class="article__form_main-description" />
    </div>

    <h3>Теги путешествия</h3>
    <div class="article__form_tags">
      <MultiSelect v-model="state.tags" display="chip" :options="tags" optionLabel="name" placeholder="Выберете тег"
                   :maxSelectedLabels="3" class="article__form_tags-select" />
    </div>
    <h3>Выберете место</h3>
    <div class="article__form_place">
      <Dropdown v-model="placeQ" @change="onSelect" @input="onInput" editable :options="places" optionLabel="name"  placeholder="Выберете тег" class="article__form_place-input" />

      <div class="article__form_place-list">
        <div v-for="(place, idx) in state.places" :key="'place'+idx+place.name" class="article__form_place-list__item">
          {{place.title}}

          <svg @click="deletePlace(idx)"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20px" height="20px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </div>
      </div>
    </div>

    <div class="buttons">
      <div class="cancel">Отменить</div>
      <button type="submit">Опубликовать</button>
    </div>
  </form>
</template>

<style lang="scss">
  .article__form {
      padding: 38px;
      padding-top: 51px;
      h3 {
        text-transform: uppercase;
        color: #FF510E;
        padding-bottom: 10px;
        margin-bottom: 32px;

        border-bottom: 1px solid #FF510E;
      }

      .buttons {
        display: flex;
        justify-content: flex-end;

        > * {
          border-radius: 9px;
          padding: 6px 16px;
        }
        .cancel {
          margin-right: 15px;
          color: #FF510E;
          background: #fff;
        }

        button {
          color: #fff;
          background: #FF510E;
        }
      }

      &_main {
        border: 0.7px solid #000;
        border-radius: 12px;
        padding: 19px 25px;
        margin-bottom: 22px;

        &-title {
          width: 100%;
          color: #8F9295;
          font-size: 18px;
          font-family: "TT Commons";
          font-weight: 800;
          margin-bottom: 8px;
        }

        &-description {
          width: 100%;
          font-size: 16px;
          font-weight: normal;
          color: #8F9295;
        }
      }

      &_tags {
        margin-bottom: 22px;

        &-select {
          width: 100%;
          background: #E6EBEF;
          box-shadow: none;
          border: 0.7px solid #000;
        }


      }
      &_place {
        &-input {
          width: 100%;
          margin-bottom: 20px;
          background: #E6EBEF;
          box-shadow: none;
          border: 0.7px solid #000;
        }

        &-list {
          margin-bottom: 20px;
          display: flex;
          flex-wrap: wrap;



          &__item {
            display: flex;
            background: #FF510E;
            color: #fff;
            border-radius: 5px;
            padding: 2px 5px;

            svg {
              cursor: pointer;
              margin-right: 5px;
            }
          }
        }
      }
  }

  .p-checkbox.p-highlight .p-checkbox-box {
    border-color: #FF510E !important;
    background: #FF510E !important;
  }
</style>