<template>
<div class="min-h-screen grid md:grid-cols-2">
    <div class="hidden md:flex flex-col justify-center items-center bg-blue-600 text-white p-12">
        <h1 class="text-4xl font-bold mb-4">
            Gestão de projetos
        </h1>
        <p class="text-lg text-blue-100 text-center max-w-md">
            Organize seus projetos, gerencia tarefas e colabore com seu time de forma eficiente.
        </p>
    </div>
    <div class="flex items-center justify-center bg-gray-50 p-6">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center">
                Entrar
            </h2>
            <form @submit.prevent="handleLogin" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Email
                    </label>
                    <input
                        v-model="email"
                        type="email"
                        placeholder="email@email.com"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                        required
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Senha
                    </label>
                    <input
                        v-model="password"
                        type="password"
                        placeholder="********"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                        required
                    />
                </div>
                <button
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
                >
                    Entrar
                </button>
            </form>
            <p class="text-sm text-center mt-4">
                Ainda não tem uma conta?
                <router-link to="/auth/register" class="text-blue-600 font-medium hover:underline">
                    Registre-se
                </router-link>
            </p>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"
import { login } from "@/services/authService"

const router = useRouter()

const email = ref("")
const password = ref("")
const error = ref(null)
const loading = ref(false)

const handleLogin = async () => {
    error.value = null
    loading.value = true

    try {
        const response = await login({
            email: email.value,
            password: password.value
        })

        localStorage.setItem("token", response.token)

        router.push("/")
    } catch (err) {
        error.value = "Credênciais invalidas!"
    } finally {
        loading.value = false
    }
}
</script>