<template>
    <aside
        v-if="photo.user"
        id="image-details-sidebar"
        ref="sidebar"
        class="
            js-image-details-sidebar
            sidebar_always-hideable sidebar_right sidebar
            image-view__details
        "
        :class="sidebarPositionClass"
        tabindex="-1"
        aria-labelledby="sidebar"
    >
        <div class="image-view__details-head sidebar__head">
            <button
                title="Close details"
                type="button"
                class="
                    js-sidebar-toggle
                    image-view__details-toggle
                    btn-subtle btn-lg btn-close
                "
                data-sidebar="#image-details-sidebar"
                aria-label="Close details"
                v-on:click="toggleSidebar"
            >
                <span class="visually-hidden">Close details</span>
            </button>
        </div>
        <div class="sidebar__body scrollbar">
            <div class="image-view__details-content">
                <ul class="nolist">
                    <li class="js-editable editable">
                        <file-title
                            v-on:title-change="photo.title = $event"
                            :id="photo.id"
                            :title="photo.title"
                            name="title"
                            >Title:
                        </file-title>
                    </li>
                    <!-- <li>
                                <dl class="dlist_0 dlist">
                                    <dt>Created:</dt>
                                    <dd>21.21.21</dd>
                                </dl>
                            </li> -->
                    <li>
                        <dl class="dlist_0 dlist">
                            <dt>Uploaded:</dt>
                            <dd>
                                {{ formatTimestamp(photo.created_at) }}
                            </dd>
                        </dl>
                    </li>
                    <li>
                        <dl class="dlist_0 dlist">
                            <dt>Dimensions:</dt>
                            <dd>{{ photo.height }}x{{ photo.width }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl class="dlist_0 dlist">
                            <dt>File size:</dt>
                            <dd>{{ bytesToSize(photo.size) }}</dd>
                        </dl>
                    </li>
                    <!-- <li>
                                <dl class="dlist_0 dlist">
                                    <dt>Family tree:</dt>
                                    <dd>
                                        <a class="link-action" href="#">Show</a>
                                    </dd>
                                </dl>
                            </li> -->
                    <li>
                        <dl class="dlist_0 dlist">
                            <dt>Uploaded by:</dt>
                            <dd>{{ photo.user.name }}</dd>
                        </dl>
                    </li>
                    <li v-if="photo.location">
                        <dl class="dlist_0 dlist">
                            <dt>Location map:</dt>
                            <dd>
                                LocationLocationLocationLocationLocationLocationLocationLocationLocation
                            </dd>
                        </dl>
                    </li>
                    <li v-if="photo.license">
                        <dl class="dlist_0 dlist">
                            <dt>Licensing and attribution:</dt>
                            <dd>
                                Whether the photographer and/or models has
                                signed an agreement with us; Whether we need to
                                give attribution; Whether someone owns the
                                copyright other than us; Whether it is stock
                                photography
                            </dd>
                        </dl>
                    </li>
                    <li>
                        <dl class="dlist_0 dlist">
                            <dt>
                                <label for="tag-input">Tags</label>
                            </dt>
                            <dd>
                                <file-tag
                                    :tags="photo.tags"
                                    :id="photo.id"
                                    v-on:add-tag="photo.tags.push($event)"
                                    v-on:remove-tag="
                                        photo.tags.splice($event, 1)
                                    "
                                >
                                </file-tag>
                            </dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
</template>

<script>
export default {
    props: ["photo"],
};
</script>
