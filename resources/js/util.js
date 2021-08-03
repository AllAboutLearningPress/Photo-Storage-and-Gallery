export const notify = (body, level, button) => {
    document.dispatchEvent(new CustomEvent('notify', {
        detail: {
            body: body,
            level: level ? level : 'info',
            button: button,
        }
    }))
}

export const axiosError = (err) => {
    notify("Something went wrong. Please try again")
}

export const updatePhotoDetails = (data) => {
    axios
        .post(route("uploads.update-details"), data)
        .then((resp) => {
            notify("Photo Updated", 'success');
        })
        .catch((err) => {
            notify("Something went Wrong", "danger");
        });
}

/**
 * Helper of `addEventListener`.
 * @param elem{HTMLElement} - element to bind event
 * @param type{String} - event type
 * @param listener{Function} - event listener
 * @param options{Boolean|Object} - optional parameter which passed as a third parameter to native `addEventListener`
 * @returns {function(): *} - the unbind callback
 */
export function addEventListener(elem, type, listener, options) {
    elem.addEventListener(type, listener, options);

    return () => elem.removeEventListener(type, listener, options);
}
