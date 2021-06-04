export const notify = (body) => {
    document.dispatchEvent(new CustomEvent('notify', {
        detail: {
            body: body
        }
    }))
}