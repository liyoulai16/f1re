<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $articles = [
            [
                'title' => 'Laravel 入门指南：从零开始构建 Web 应用',
                'slug' => 'getting-started-with-laravel',
                'excerpt' => '本文将带你了解 Laravel 框架的核心概念，包括路由、控制器、Eloquent ORM 和 Blade 模板引擎，帮助你快速上手 PHP 现代化开发。',
                'body' => '<h2>什么是 Laravel？</h2><p>Laravel 是一个优雅的 PHP Web 应用框架，它提供了丰富的功能来简化常见的开发任务，如路由、认证、会话管理和缓存。</p><h2>安装与配置</h2><p>使用 Composer 可以轻松创建一个新的 Laravel 项目：</p><pre><code>composer create-project laravel/laravel myapp</code></pre><p>安装完成后，配置 <code>.env</code> 文件中的数据库连接信息，然后运行迁移命令来创建数据表。</p><h2>路由基础</h2><p>Laravel 的路由系统非常直观。你可以在 <code>routes/web.php</code> 中定义 Web 路由，在 <code>routes/api.php</code> 中定义 API 路由。</p><h2>Eloquent ORM</h2><p>Eloquent 是 Laravel 内置的 ORM，它为数据库操作提供了简洁优雅的 ActiveRecord 实现。每个数据表都有一个对应的模型类用于与该表交互。</p>',
                'published_at' => '2024-01-15 10:00:00',
            ],
            [
                'title' => 'Vue 3 组合式 API 完全指南',
                'slug' => 'vue3-composition-api-guide',
                'excerpt' => '深入理解 Vue 3 的 Composition API，学习 ref、reactive、computed 和 watch 等核心概念，掌握现代 Vue 开发的最佳实践。',
                'body' => '<h2>为什么选择组合式 API？</h2><p>Vue 3 引入了组合式 API (Composition API)，它提供了一种更灵活的方式来组织组件逻辑。相比选项式 API，组合式 API 让相关逻辑可以放在一起，提高代码的可读性和可复用性。</p><h2>ref 与 reactive</h2><p><code>ref</code> 用于创建基础类型的响应式数据，<code>reactive</code> 则用于对象类型。两者的核心区别在于访问方式：ref 需要通过 <code>.value</code> 访问，而 reactive 可以直接访问属性。</p><h2>computed 计算属性</h2><p>计算属性会自动追踪依赖并缓存结果，只有当依赖发生变化时才会重新计算。</p><h2>生命周期钩子</h2><p>在组合式 API 中，生命周期钩子以 <code>onMounted</code>、<code>onUpdated</code> 等函数形式提供。</p>',
                'published_at' => '2024-02-08 14:30:00',
            ],
            [
                'title' => 'Tailwind CSS：实用优先的 CSS 框架',
                'slug' => 'tailwind-css-utility-first',
                'excerpt' => '探索 Tailwind CSS 的实用优先理念，了解如何通过组合工具类快速构建现代化的响应式界面，告别传统 CSS 的繁琐。',
                'body' => '<h2>实用优先的理念</h2><p>与 Bootstrap 等传统框架不同，Tailwind CSS 提供的是低层级的工具类，而不是预设的组件样式。这意味着你可以完全自由地构建独特的设计，而不会被框架的视觉风格所限制。</p><h2>响应式设计</h2><p>Tailwind 使用移动优先的断点系统。通过前缀如 <code>sm:</code>、<code>md:</code>、<code>lg:</code>，你可以轻松地为不同屏幕尺寸定制样式。</p><h2>深色模式</h2><p>通过 <code>dark:</code> 前缀，实现深色模式变得异常简单。Tailwind 支持基于系统偏好或手动切换的深色模式。</p><h2>自定义配置</h2><p>通过 <code>tailwind.config.js</code> 文件，你可以自定义颜色、间距、字体等所有设计令牌，打造属于你的设计系统。</p>',
                'published_at' => '2024-03-01 09:15:00',
            ],
            [
                'title' => 'SQLite：轻量级数据库的强大之处',
                'slug' => 'sqlite-lightweight-database',
                'excerpt' => '了解 SQLite 的优势和适用场景，学习如何在开发和小规模生产环境中充分利用这个零配置的嵌入式数据库引擎。',
                'body' => '<h2>什么是 SQLite？</h2><p>SQLite 是一个自包含、无服务器、零配置的事务性 SQL 数据库引擎。它是世界上部署最广泛的数据库，被用于无数的应用程序和设备中。</p><h2>核心优势</h2><ul><li><strong>零配置</strong>：无需安装或管理，数据库就是一个普通文件</li><li><strong>跨平台</strong>：数据库文件可以在不同操作系统间自由复制</li><li><strong>可靠性</strong>：经过严格的测试，代码覆盖率达到 100%</li><li><strong>高性能</strong>：对于中小规模的读操作，性能常常优于客户端-服务器数据库</li></ul><h2>适用场景</h2><p>SQLite 适合嵌入式设备、桌面应用、网站（中低流量）、数据分析和原型开发。对于需要高并发写入的场景，则建议使用 PostgreSQL 或 MySQL。</p>',
                'published_at' => '2024-03-20 16:00:00',
            ],
            [
                'title' => 'Web 安全认证最佳实践',
                'slug' => 'web-authentication-best-practices',
                'excerpt' => '从密码哈希到会话管理，从 CSRF 防护到 JWT，全面介绍 Web 应用认证安全的关键要素和实施建议。',
                'body' => '<h2>密码存储</h2><p>永远不要以明文形式存储密码。使用 bcrypt 或 Argon2 等专用哈希算法，它们具有自适应的计算成本，能有效抵抗暴力破解。</p><h2>会话管理</h2><p>会话是维持用户认证状态的常见方式。关键实践包括：</p><ul><li>登录后重新生成会话 ID</li><li>设置合理的会话过期时间</li><li>使用 HttpOnly 和 Secure 标志保护 Cookie</li></ul><h2>CSRF 防护</h2><p>跨站请求伪造（CSRF）攻击利用用户的已认证会话执行恶意操作。使用 CSRF Token 可以有效防御此类攻击。</p><h2>多因素认证</h2><p>仅依赖密码是不够的。考虑实现 TOTP（基于时间的一次性密码）或 WebAuthn 作为第二因素。</p>',
                'published_at' => '2024-04-10 11:00:00',
            ],
            [
                'title' => '现代前端开发工作流：从构建工具到部署',
                'slug' => 'modern-frontend-workflow',
                'excerpt' => '介绍现代前端开发的完整工作流，涵盖包管理、模块打包、代码检查、测试和自动化部署等环节。',
                'body' => '<h2>包管理器</h2><p>npm 和 pnpm 是目前最流行的 JavaScript 包管理器。pnpm 通过硬链接和内容寻址存储，显著减少磁盘占用和安装时间。</p><h2>构建工具</h2><p>Vite 已经成为现代前端项目的首选构建工具。它利用浏览器原生 ES 模块，在开发时提供极快的热更新（HMR），生产构建则使用 Rollup 进行优化打包。</p><h2>代码质量</h2><p>ESLint 用于检查代码质量和潜在错误，Prettier 负责统一代码格式。结合 husky 和 lint-staged，可以在提交前自动运行检查。</p><h2>持续集成</h2><p>使用 GitHub Actions 或 GitLab CI，每次推送都会自动运行测试和构建，确保代码质量不会随时间退化。</p>',
                'published_at' => '2024-05-05 08:30:00',
            ],
        ];

        foreach ($articles as $article) {
            Article::create(array_merge($article, ['is_published' => true]));
        }
    }
}
