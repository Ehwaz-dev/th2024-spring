<script setup lang="ts">



import {formatDateTime} from "~/utils/date";

const route = useRoute()
const api = useApi()
// @ts-ignore
const {data} = await api.users.find(route.params.id)
const user = useState<any>(() => ({
  ...data,
  articles: data.articles.map(a => ({
    ...a,
    edit: false,
    open: false,
  }))
}))

const editAbout = ref(false)
const toggleEditAbout = async () => {
  editAbout.value = !editAbout.value

  if (editAbout.value) {
    return
  }

  await api.users.update(user.value.id, {about: user.value.about});
}

const editName = ref(false)
const toggleEditName = async () => {
  editName.value = !editName.value

  if (editName.value) {
    return
  }

  await api.users.update(user.value.id, {name: user.value.name});
}

const {like, comment} = useLikeComment()

const editArticle = (article: any) => {
  article.edit = !article.edit

  if (article.edit) {
    return
  }

  api.articles.update(article.id, article)
}

const popupArticle = ref(true)
</script>

<template>
  <UiPopup v-model="popupArticle" class="popup_contactus">
    <ArticleCreateForm/>
  </UiPopup>

  <div class="profile__wrapper">
    <div class="profile__wrapper__info">
      <div class="info__avatar">
        <NuxtImg src="/img/Profile-avatar.svg"/>
      </div>
      <div class="info__info">
        <h5 v-if="!editName">{{user.name}}</h5>
        <InputText  v-else
                    v-model="user.name"
                    :unstyled="true"
                    @keydown.enter.exact.prevent="toggleEditName"
                    type="text" />
        <button @click="toggleEditName"><NuxtImg src="/img/Settings-icon.svg"/></button>
<!--        <p>Возраст</p>-->
<!--        <p>Город</p>-->
      </div>
<!--      <div class="info__achievments">-->
<!--        <h5>Достижения</h5>-->
<!--        <div class="achievments">-->
<!--          <div class="achievments__item"></div>-->
<!--          <div class="achievments__item"></div>-->
<!--          <div class="achievments__item"></div>-->
<!--          <div class="achievments__item"></div>-->
<!--          <div class="achievments__item"></div>-->
<!--        </div>-->
<!--      </div>-->
    </div>
    <div class="profile__wrapper__posts">
      <div class="posts__about">
        <h5>ОБ АВТОРЕ</h5>
        <button @click="toggleEditAbout"><NuxtImg src="/img/Redact-icon.svg"/></button>
        <div class="content" v-html="$mdRenderer.render(user.about || '')"></div>

        <Textarea :unstyled="true" v-show="editAbout"
                  v-model="user.about"
                  @keydown.enter.exact.prevent="toggleEditAbout"
                  @keydown.enter.shift.exact.prevent="user.about += '\n'"
                  autoResize rows="5" cols="30" />
      </div>
      <button class="posts_add">
        <NuxtImg src="/img/plus.svg"/>
      </button>
      <div v-for="article in user.articles" class="posts__item__1">
        <p class="post_time">{{formatDateTime(article.created_at)}}</p>
        <div class="Zakrep_and_redact">
          <button @click="editArticle(article)"><NuxtImg src="/img/Redact-black-icon.svg"/></button>
        </div>
        <h5 v-if="!article.edit">{{ article.title}}</h5>
        <InputText
                    v-else
                    v-model="article.title"
                    :unstyled="true"
                    @keydown.enter.exact.prevent="editArticle(article)"
                    class="title_edit"
                    type="text" />

        <div class="post__text__wrapper" :class="{'text_hidden': !article.edit && !article.open}">
          <div class="post__text" v-html="$mdRenderer.render(article.description)"></div>
          <div v-if="!article.edit" @click="article.open = !article.open" class="show">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="25px">
              <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
          </div>
        </div>
        <Textarea :unstyled="true" v-if="article.edit"
                  v-model="article.description"
                  @keydown.enter.exact.prevent="editArticle(article)"
                  @keydown.enter.shift.exact.prevent="article.description += '\n'"
                  autoResize rows="5" cols="30" class="textarea_edit" />
        <NuxtImg class="post__img" src="/img/AwesomePhotoKrasnodarskihGor.png"/>
        <div class="likes_and_comments">
          <button @click="like(article, 'articles')"><NuxtImg src="/img/Like-icon.svg"/><p>{{ article.likes.count || 0 }}</p></button>
          <button><NuxtImg src="/img/Comment-icon.svg"/><p>{{ article.comments.count || 0 }}</p></button>
        </div>
        <Comment @comment="(c) => comment(article, 'articles', c)"/>
        <Comments :comments="article.comments.list" />
      </div>
    </div>
    <div class="profile__wrapper__travels">
      <div class="travels__active">
        <div>
          <h5>ТЕКУЩЕЕ ПУТЕШЕСТВИЕ</h5>
          <NuxtImg src="/img/plus-orange.svg"/>
        </div>
      </div>
      <div v-for="event in user.events.active" class="travels__active__item">
        <figure>
          <NuxtImg src="/img/Travel-pic.svg"/>
          <NuxtImg class="Gradient-pic" src="/img/Gradient.svg"/>
          <h6>{{ event.name }}</h6>
          <p>{{formatDateRange(event.dates.start, event.dates.end)}}</p>
        </figure>
        <figcaption>
          <p>{{event.description}}</p>
        </figcaption>
        <div class="tags item-tags">
          <div v-for="tag in event.tags" class="tag-item circle"></div>
        </div>
        <div v-if="event.maxUsers" class="item-amount">
          <NuxtImg src="/img/User-icon.svg"/>
          <p>{{event.maxUsers}}</p>
        </div>
        <button class="Share-btn"><NuxtImg src="/img/Share-icon.svg"/></button>
      </div>
      <div v-if="user.events.passed.length" class="travels__last">
        <h5>ПРОШЕДШИЕ ПУТЕШЕСТВИЯ</h5>
      </div>
      <div v-for="event in user.events.passed" class="travels__active__item">
        <figure>
          <NuxtImg src="/img/Travel-pic.svg"/>
          <NuxtImg class="Gradient-pic" src="/img/Gradient.svg"/>
          <h6>{{ event.name }}</h6>
          <p>{{formatDateRange(event.dates.start, event.dates.end)}}</p>
        </figure>
        <figcaption>
          <p>{{event.description}}</p>
        </figcaption>
        <div class="tags item-tags">
          <div v-for="tag in event.tags" class="tag-item circle"></div>
        </div>
        <div v-if="event.maxUsers" class="item-amount">
          <NuxtImg src="/img/User-icon.svg"/>
          <p>{{event.maxUsers}}</p>
        </div>
        <button class="Share-btn"><NuxtImg src="/img/Share-icon.svg"/></button>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>