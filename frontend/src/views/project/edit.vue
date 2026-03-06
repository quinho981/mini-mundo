<template>
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-2xl mx-auto bg-white shadow rounded-xl p-6">
        <h1 class="text-2xl font-bold mb-6">
            Editar Projeto
        </h1>
        <form v-if="loaded" @submit.prevent="updateProject" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">
                    Nome
                </label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full border rounded-lg px-3 py-2"
                />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">
                    Descrição
                </label>
                <textarea
                    v-model="form.description"
                    class="w-full border rounded-lg px-3 py-2"
                />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">
                    Status
                </label>
                <select
                    v-model="form.status"
                    class="w-full border rounded-lg px-3 py-2"
                >
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">
                    Orçamento
                </label>
                <input
                    v-model="form.budget"
                    type="number"
                    step="0.01"
                    class="w-full border rounded-lg px-3 py-2"
                />
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button
                    type="button"
                    @click="router.push('/projects')"
                    class="px-4 py-2 border rounded-lg"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Atualizar
                </button>
            </div>
        </form>
        <div v-else class="text-center py-10">
            Carregando projeto...
        </div>
    </div>
</div>
</template>

<script setup>
import { reactive, ref, onMounted } from "vue"
import axios from "axios"
import { useRoute, useRouter } from "vue-router"

const router = useRouter()
const route = useRoute()

const loaded = ref(false)

const form = reactive({
    name: "",
    description: "",
    status: "",
    budget: ""
})

const fetchProject = async () => {
    try {
        const token = localStorage.getItem("token")

        const response = await axios.get(
            `http://localhost:8000/api/projects/${route.params.id}`,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        Object.assign(form, response.data)
        loaded.value = true
    } catch (error) {
        console.error(error)
    }
}

const updateProject = async () => {
    try {
        const token = localStorage.getItem("token")

        await axios.patch(
            `http://localhost:8000/api/projects/${route.params.id}`,
            form,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        router.push("/projects")
    } catch (error) {
        console.error(error)
    }
}

onMounted(fetchProject)
</script>