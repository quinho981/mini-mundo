<template>
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-2xl mx-auto bg-white shadow rounded-xl p-6">
        <h1 class="text-2xl font-bold mb-6">
            Novo Projeto
        </h1>
        <form @submit.prevent="createProject" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">
                    Nome
                </label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full border rounded-lg px-3 py-2"
                    required
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
                    <option value="active">Ativo</option>
                    <option value="inactive">Inativo</option>
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
                    :disabled="loading"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                >
                    <span v-if="loading">
                        Criando...
                    </span>

                    <span v-else>
                        Criar Projeto
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
</template>

<script setup>
import { reactive, ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

const router = useRouter()

const loading = ref(false)
const form = reactive({
    name: "",
    description: "",
    status: "active",
    budget: ""
})

const createProject = async () => {
    loading.value = true
    try {
        const token = localStorage.getItem("token")
        const payload = {
            ...form,
            budget: Number(form.budget).toFixed(2)
        }

        await axios.post(
            "http://localhost:8000/api/projects",
            payload,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        router.push("/projects")
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}
</script>