<template>
  <div>
    <div class="page-header">
      <h2>Media Library</h2>
    </div>
    <p class="section-desc">Upload images to the server. Copy the URL to use it in event hero images or gallery.</p>

    <!-- Upload zone -->
    <div
      class="upload-zone"
      :class="{ dragging: isDragging, uploading: uploading }"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="onDrop"
      @click="fileInput.click()"
    >
      <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/gif,image/webp" multiple class="file-input-hidden" @change="onFileChange" />
      <div class="upload-zone-inner">
        <div class="upload-icon">🖼️</div>
        <p class="upload-label">{{ uploading ? 'Uploading…' : 'Click or drag images here to upload' }}</p>
        <p class="upload-hint">JPEG, PNG, GIF, WebP · max 8 MB each</p>
      </div>
    </div>

    <div v-if="uploadError" class="alert alert-error">{{ uploadError }}</div>
    <div v-if="uploadSuccess" class="alert alert-success">{{ uploadSuccess }}</div>

    <!-- Image grid -->
    <div v-if="loadError" class="alert alert-error">{{ loadError }}</div>
    <div v-else-if="loading" class="loading-msg">Loading…</div>
    <template v-else>
      <p v-if="!images.length" class="empty-msg">No images uploaded yet.</p>
      <div v-else class="media-grid">
        <div v-for="img in images" :key="img.filename" class="media-card">
          <div class="media-thumb-wrap">
            <img :src="img.url" :alt="img.filename" class="media-thumb" loading="lazy" />
          </div>
          <div class="media-card-footer">
            <span class="media-filename" :title="img.filename">{{ img.filename }}</span>
            <div class="media-actions">
              <button class="media-btn media-btn-copy" :class="{ copied: copiedUrl === img.url }" @click="copyUrl(img.url)" :title="copiedUrl === img.url ? 'Copied!' : 'Copy URL'">
                {{ copiedUrl === img.url ? '✓' : '⎘' }}
              </button>
              <button class="media-btn media-btn-delete" @click="deleteImage(img)" title="Delete">✕</button>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const { public: { apiBaseUrl } } = useRuntimeConfig()
const base = apiBaseUrl.replace(/\/$/, '')

const images       = ref([])
const loading      = ref(true)
const loadError    = ref('')
const uploading    = ref(false)
const uploadError  = ref('')
const uploadSuccess = ref('')
const isDragging   = ref(false)
const copiedUrl    = ref('')
const fileInput    = ref(null)

async function loadImages() {
  loading.value  = true
  loadError.value = ''
  try {
    const res = await useAdminFetch(`${base}/admin/list-images.php`)
    images.value = res?.images ?? []
  } catch (e) {
    loadError.value = e?.data?.error ?? 'Failed to load images.'
  } finally {
    loading.value = false
  }
}

onMounted(loadImages)

async function uploadFiles(files) {
  if (!files.length) return
  uploading.value   = true
  uploadError.value  = ''
  uploadSuccess.value = ''

  // Get session cookie name for auth
  const sessionHint = useCookie('admin_session_hint')

  let uploaded = 0
  for (const file of files) {
    const fd = new FormData()
    fd.append('file', file)
    try {
      await $fetch(`${base}/admin/upload-image.php`, {
        method: 'POST',
        body: fd,
        credentials: 'include',
      })
      uploaded++
    } catch (e) {
      const msg = e?.data?.error ?? e?.message ?? 'Upload failed'
      uploadError.value = `${file.name}: ${msg}`
    }
  }

  uploading.value = false
  if (uploaded) {
    uploadSuccess.value = `${uploaded} image${uploaded > 1 ? 's' : ''} uploaded.`
    setTimeout(() => { uploadSuccess.value = '' }, 4000)
    await loadImages()
  }
}

function onFileChange(e) {
  uploadFiles([...e.target.files])
  e.target.value = ''
}

function onDrop(e) {
  isDragging.value = false
  const files = [...e.dataTransfer.files].filter(f => f.type.startsWith('image/'))
  uploadFiles(files)
}

async function deleteImage(img) {
  if (!confirm(`Delete "${img.filename}"?`)) return
  try {
    await useAdminFetch(`${base}/admin/delete-image.php`, {
      method: 'POST',
      body: { filename: img.filename },
    })
    images.value = images.value.filter(i => i.filename !== img.filename)
  } catch (e) {
    uploadError.value = e?.data?.error ?? 'Delete failed.'
  }
}

function copyUrl(url) {
  navigator.clipboard.writeText(url).then(() => {
    copiedUrl.value = url
    setTimeout(() => { copiedUrl.value = '' }, 2000)
  })
}
</script>

<style scoped>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.section-desc { color: #666; font-size: 14px; margin-bottom: 20px; }

/* Upload zone */
.upload-zone {
  border: 2px dashed #d0d0d0;
  border-radius: 12px;
  padding: 40px 24px;
  text-align: center;
  cursor: pointer;
  transition: border-color 0.15s, background 0.15s;
  margin-bottom: 20px;
  background: #fafafa;
}
.upload-zone:hover, .upload-zone.dragging { border-color: #2563EB; background: #EFF6FF; }
.upload-zone.uploading { pointer-events: none; opacity: 0.6; }
.file-input-hidden { display: none; }
.upload-icon { font-size: 36px; margin-bottom: 10px; }
.upload-label { font-size: 15px; font-weight: 600; color: #333; margin: 0 0 6px; }
.upload-hint { font-size: 13px; color: #888; margin: 0; }

/* Grid */
.media-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 16px;
  margin-top: 8px;
}
.media-card {
  border: 1px solid #e8e8e8;
  border-radius: 10px;
  overflow: hidden;
  background: #fff;
  transition: box-shadow 0.15s;
}
.media-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
.media-thumb-wrap { width: 100%; aspect-ratio: 4/3; overflow: hidden; background: #f0f0f0; }
.media-thumb { width: 100%; height: 100%; object-fit: cover; display: block; }
.media-card-footer { padding: 8px 10px; display: flex; align-items: center; justify-content: space-between; gap: 6px; border-top: 1px solid #f0f0f0; }
.media-filename { font-size: 11px; color: #666; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex: 1; min-width: 0; }
.media-actions { display: flex; gap: 4px; flex-shrink: 0; }
.media-btn { width: 28px; height: 28px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; display: flex; align-items: center; justify-content: center; transition: background 0.15s; }
.media-btn-copy { background: #EFF6FF; color: #2563EB; }
.media-btn-copy:hover, .media-btn-copy.copied { background: #2563EB; color: #fff; }
.media-btn-delete { background: #FEF2F2; color: #c41e3a; }
.media-btn-delete:hover { background: #c41e3a; color: #fff; }

/* Misc */
.empty-msg { color: #999; font-size: 14px; padding: 20px 0; }
.loading-msg { color: #888; padding: 20px 0; }
.alert-error   { background: #fdecea; color: #c00; padding: 10px 14px; border-radius: 6px; margin: 12px 0; font-size: 14px; }
.alert-success { background: #e6f4ea; color: #1a7a3a; padding: 10px 14px; border-radius: 6px; margin: 12px 0; font-size: 14px; }
</style>
