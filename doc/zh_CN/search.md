搜索语法
=======

Hiject可使用非常的查询语言进行高级搜索。 
你可以在任务、子任务、评论、链接及动态等范围内进行搜索。

查询例子
-------

这个例子会返回所有 指派给我、明天到期并且标题中包含 “my title” 的任务

```
assigne:me due:tomorrow my title
```

全局搜索
-------

### 按任务ID或标题搜索

- 按任务ID: `#123`
- 按任务ID和标题: `123`
- 按任务标题: 只要不命中搜索属性，均被视为按任务标题搜索

### 按状态搜索

属性: **status**

- 查询所有打开的任务: `status:open`
- 查询所有关闭的任务: `status:closed`

### 按指派人搜索

属性: **assignee**

- 按姓名搜索: `assignee:"Guan Shiliang"`
- 按用户名: `assignee:guanshiliang`
- 多指派人搜索: `assignee:user1 assignee:"John Doe"`
- 没有被指派的任务: `assignee:nobody`
- 指派给我的任务: `assignee:me`

### 按任务创建者搜索

属性: **creator**

- 我创建的任务: `creator:me`
- John Doe创建的任务: `creator:"John Doe"`
- ID为 #1 的用户创建的任务: `creator:1`

### 根据指派人搜索子任务

属性: **subtask:assignee**

- 例: `subtask:assignee:"John Doe"`

### 按颜色搜索

属性: **color**

- 按颜色标识搜索: `color:blue`
- 按颜色名称搜索: `color:"橘红色"`

### 按到期时间搜索

属性: **due**

- 今天到期的任务: `due:today`
- 明天到期的任务: `due:tomorrow`
- 昨天到期的任务: `due:yesterday`
- 指定的到期时间: `due:2015-06-29`

时间格式必须为 ISO 8601 格式: **YYYY-MM-DD**.

所有关于时间的字符串应该能被PHP原生函数 `strtotime()` 解析, 例如 `next Thursday`, `-2 days`, `+2 months`, `tomorrow`, 等.

支持的时间操作符:

- 大于: **due:>2015-06-29**
- 小于: **due:<2015-06-29**
- 大于等于: **due:>=2015-06-29**
- 小于等于: **due:<=2015-06-29**

### 按更新时间搜索

属性: **updated** or **modified**

时间格式参照`到期时间`搜索.

支持过滤`刚刚`有更新的任务: `modified:recently`.

此处所认定的`刚刚`与项目设定的`高亮期限`的时间一致.

### 按创建日期搜索

属性: **created**

时间格式参照`更新时间`搜索.

### 按开始日期搜索

属性: **started**

### 按描述搜索

属性: **description** or **desc**

例: `description:"text search"`

### 按外部引用搜索

来自其他系统的编号.

- 找出所有外部编号为1234的任务: `ref:1234` or `reference:TICKET-1234`

### 按分类搜索

属性: **category**

- 按分类名称搜索: `category:"Feature Request"`
- 在多个分类中搜索: `category:"Bug" category:"Improvements"`
- 搜索为制定分类的任务: `category:none`

### 按项目搜索

属性: **project**

- 按项目名搜索: `project:"My project name"`
- 按项目ID搜搜: `project:23`
- 在多个项目中搜索: `project:"My project A" project:"My project B"`

### 按栏目搜索

属性: **column**

- 按栏目名称搜索: `column:"Work in progress"`
- 在多个栏目中进行搜索: `column:"Backlog" column:ready`

### 按泳道搜索

属性: **swimlane**

- 按泳道名称搜索: `swimlane:"Version 42"`
- 在默认泳道中搜索: `swimlane:default`
- 在多个泳道中搜索: `swimlane:"Version 1.2" swimlane:"Version 1.3"`

### 按任务`关联`搜索

属性: **link**

- 按关联名称搜索: `link:"is a milestone of"`
- 在多个关联中搜索: `link:"is a milestone of" link:"relates to"`

### 按评论搜索

属性: **comment**

- 评论标题包含关键词的搜索: `comment:"My comment message"`

### 按标签搜索

属性: **tag**

- 例: `tag:"My tag"`

动态搜索
-------

### 按任务名称进行动态搜索

属性: **title** or none (默认)

- 如: `title:"My task"`
- 按任务ID: `#123`

### 按任务状态进行动态搜索

属性: **status**

### 按创建者进行动态搜索

属性: **creator**

### 按创建时间进行动态搜索

属性: **created**

### 按项目进行动态搜索

属性: **project**
