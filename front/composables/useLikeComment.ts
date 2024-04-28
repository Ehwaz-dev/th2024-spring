export const useLikeComment = () => {

  const api = useApi()

  const makeAction = async (resource: any, resourceType: string, action: string, payload = "") => {
    const res = await api[resourceType][action](resource.id, payload)
    resource[action + "s"].count = res.count

    if (action == 'comment') {
      console.log("hasdas")
      resource[action + "s"].list = [res.comment, ...resource[action + "s"].list]
    }
  }

  return {
    like: (resource: any, resourceType: string) => makeAction(resource, resourceType, 'like'),
    comment: (resource: any, resourceType: string, comment: string) => makeAction(resource, resourceType, 'comment', comment)
  }
}