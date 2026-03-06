<template>
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">
                    {{ project?.name }}
                </h1>
                <p class="text-gray-500 mt-1">
                    {{ project?.description || "Sem descrição" }}
                </p>
            </div>
            <div class="flex gap-3">
                <button
                    @click="$router.push('/projects')"
                    class="bg-neutral-500 text-white px-4 py-2 rounded-lg hover:bg-neutral-600"
                >
                    Voltar
                </button>
                <button
                    @click="editProject"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                >
                    Editar
                </button>
                <button
                    @click="deleteProject"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                >
                    Deletar
                </button>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">
                    Status
                </p>
                <p class="text-lg font-semibold mt-1">
                    {{ translateProjectStatus(project?.status) }}
                </p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">
                    Orçamento
                </p>
                <p class="text-lg font-semibold mt-1">
                    R$ {{ formatBudget(project?.budget) }}
                </p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">
                    Tarefas
                </p>
                <p class="text-lg font-semibold mt-1">
                    {{ tasks.length }}
                </p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-semibold">
                    Tarefas
                </h2>
                <button
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                >
                    + Nova tarefa
                </button>
            </div>
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Descrição
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Data início
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Data fim
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="task in tasks"
                        :key="task.id"
                        class="border-t hover:bg-gray-50"
                    >
                        <td class="px-6 py-4">
                            {{ task.description }}
                        </td>
                        <td class="px-6 py-4">
                            {{ translateTaskStatus(task.status) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ formatDateBR(task.start_date) || "—" }}
                        </td>
                        <td class="px-6 py-4">
                            {{ formatDateBR(task.end_date) || "—" }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-left gap-2">
                                <router-link
                                    :to="`/projects/${project.id}`"
                                    class="text-blue-600 hover:underline text-sm"
                                >
                                    Ver
                                </router-link>
                                <button
                                    @click="editProject(project.id)"
                                    class="text-yellow-600 hover:underline text-sm"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deleteProject(project.id)"
                                    class="text-red-600 hover:underline text-sm"
                                >
                                    Deletar
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="tasks.length === 0">
                        <td colspan="4" class="text-center py-10 text-gray-500">
                            Sem tarefas ainda
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import axios from "axios"

const route = useRoute()
const router = useRouter()

const project = ref(null)
const tasks = ref([])
const loading = ref(false)

const fetchProject = async () => {
    loading.value = true

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

        project.value = response.data
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}

const fetchTasks = async () => {
    loading.value = true

    try {
        const token = localStorage.getItem("token")

        const response = await axios.get(
            `http://localhost:8000/api/projects/${route.params.id}/tasks`,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        tasks.value = response.data ?? []
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}

const editProject = () => {
    router.push(`/projects/${route.params.id}/edit`)
}

const deleteProject = async () => {
    if (!confirm("Are you sure you want to delete this project?")) return

    try {
        const token = localStorage.getItem("token")

        await axios.delete(
            `http://localhost:8000/api/projects/${route.params.id}`,
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

const formatBudget = (value) => {
    if (!value) return "0.00"

    return Number(value).toFixed(2)
}

const translateProjectStatus = (status) => {
    if (status == 'active') return 'Ativo'
    if (status == 'inactive') return 'Inativo'
}

const translateTaskStatus = (status) => {
    if (status == 'completed') return 'Concluído'
    if (status == 'not_completed') return 'Não concluído'
}

const formatDateBR = (dateString) => {
    const date = new Date(dateString);

    return date.toLocaleDateString('pt-BR', {
        timeZone: 'UTC'
    });
}

onMounted(() => {
    fetchProject()
    fetchTasks()
})
</script>