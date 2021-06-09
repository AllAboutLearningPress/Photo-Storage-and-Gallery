export const notify = (body, level) => {
    document.dispatchEvent(new CustomEvent('notify', {
        detail: {
            body: body,
            level: level ? level : 'info'
        }
    }))
}
