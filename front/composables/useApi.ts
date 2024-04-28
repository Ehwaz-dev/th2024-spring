import qs from 'qs'

export const useApi = () => {
  const config = useRuntimeConfig().public
  const api = config.backendUrl


  return {
    events: {
      index: (data: any) => $fetch(`${api}/events?${qs.stringify(data)}`),
      update: (id, data: any) => $fetch(`${api}/events/${id}`, {
        method: "PUT",
        body: {
          ...data,
        }
      }),
      create: (data: any) => $fetch(`${api}/events`, {
        method: "POST",
        body: {
          ...data,
        }
      }),
    },

    users: {
      find: (id: any) => $fetch(`${api}/users/${id}`),
      update: (id, data: any) => $fetch(`${api}/users/${id}`, {
        method: "PUT",
        body: {
          ...data,
        }
      })
    },

    places: {
      search: (q: string) => $fetch(`${api}/places?q=${q}`)
    },
    articles: {
      index: (data: any) => $fetch(`${api}/articles`),
      find: (slug: any) => $fetch(`${api}/articles/${slug}`),
      update: (id, data: any) => $fetch(`${api}/articles/${id}`, {
        method: "PUT",
        body: {
          ...data,
        }
      }),
      create: (id, data: any) => $fetch(`${api}/articles/${id}`, {
        method: "PUT",
        body: {
          ...data,
        }
      }),
      like: (id, payload) => $fetch(`${api}/articles/${id}/like`, {
        method: "POST",
        body: {}
      }),
      comment: (id, comment: string) => $fetch(`${api}/articles/${id}/comment`, {
        method: "POST",
        body: {
          comment,
        }
      })
    }
  }
}